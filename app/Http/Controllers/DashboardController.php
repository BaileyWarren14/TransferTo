<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dutystatuslog; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class DashboardController extends Controller
{
    //
    public function index()
    {
        $driver = Auth::guard('driver')->user();
        $timers = $this->computeTimersForDriver($driver->id);

        // pásale 'initialTimers' para que el blade lo use directamente
        return view('driver.dashboard', [
            'initialTimers' => [
                'drive_remaining' => $timers['drive_remaining'],
                'shift_remaining' => $timers['shift_remaining'],
                'cycle_remaining' => $timers['cycle_remaining'],
                'current_status' => $timers['current_status'],
                'last_log' => $timers['last_log'] ?? null,
                'reset_point' => $timers['reset_point'] ?? null,
                'cycle_window_start' => $timers['cycle_window_start'] ?? null,
            ]
        ]);
    }

    public function newindex()
    {
        $driver = Auth::guard('driver')->user();
        $timers = $this->computeTimersForDriver($driver->id);

        return view('driver.dashboard', [
            'serverTimers' => $timers
        ]);
    }


    protected function computeTimersForDriver($driverId)
    {
        // CONSTANTES (segundos)
        $H11 = 11 * 3600;
        $H14 = 14 * 3600;
        $H70 = 70 * 3600;
        $OFF_RESET_SECONDS = 10 * 3600; // 10 horas para reset Drive/Shift

        $now = Carbon::now('UTC');

        // 1) Obtener logs recientes del conductor (ej. últimos 30 días por seguridad)
        $from = $now->copy()->subDays(30);
        $logs = dutystatuslog::where('driver_id', $driverId)
                    ->where('changed_at', '>=', $from)
                    ->orderBy('changed_at', 'asc')
                    ->get();

        // Si no hay logs: devolvemos valores por defecto
        if ($logs->isEmpty()) {
            return [
                'drive_remaining' => $H11,
                'shift_remaining' => $H14,
                'cycle_remaining' => $H70,
                'current_status' => 'OFF',
                'last_log' => null,
                'reset_point' => $now->toDateTimeString(),
                'cycle_window_start' => $now->copy()->subDays(7)->toDateTimeString(),
            ];
        }

        // 2) Encontrar último OFF >= 10h para reset de Drive/Shift.
        //    Recorremos cada intervalo (log_i -> next_log) y si status === 'OFF' y su duración >= 10h,
        //    guardamos el 'end' como resetPoint. Se usa el último OFF >=10h.
        $lastOffResetEnd = null;
        for ($i = 0; $i < $logs->count(); $i++) {
            $log = $logs[$i];
            $start = Carbon::parse($log->changed_at)->setTimezone('UTC');
            $end = ($i + 1 < $logs->count()) ? Carbon::parse($logs[$i + 1]->changed_at)->setTimezone('UTC') : $now;

            if ($log->status === 'OFF') {
                $duration = $end->diffInSeconds($start);
                if ($duration >= $OFF_RESET_SECONDS) {
                    // reset point es el final de ese OFF largo
                    $lastOffResetEnd = $end;
                }
            }
        }

        // Si no encontramos OFF>=10h, usamos inicio del día UTC como mínimo resetPoint
        $startOfDayUTC = Carbon::now('UTC')->startOfDay();
        $resetPoint = $lastOffResetEnd ?? $startOfDayUTC;

        // 3) Preparar acumuladores
        $driveAccum = 0; // segundos de Driving (D) desde resetPoint
        $shiftAccum = 0; // segundos OnDuty (no OFF ni SB) desde resetPoint
        $cycleAccum = 0; // segundos no-paused en ventana de 7 días (rolling)

        $cycleWindowStart = $now->copy()->subDays(7); // ventana de 7 días para cycle

        // 4) Recorrer logs y acumular correctamente, cortando intervalos por resetPoint y por ventana de cycle
        for ($i = 0; $i < $logs->count(); $i++) {
            $log = $logs[$i];
            $start = Carbon::parse($log->changed_at)->setTimezone('UTC');
            $end = ($i + 1 < $logs->count()) ? Carbon::parse($logs[$i + 1]->changed_at)->setTimezone('UTC') : $now;

            // --- Parte A: acumulación desde resetPoint para drive y shift ---
            // Si el intervalo está completamente antes del resetPoint, lo ignoramos.
            if ($end->lte($resetPoint)) {
                // nada
            } else {
                // cortar el inicio del segmento al resetPoint si empezaba antes
                $segStart = $start->lt($resetPoint) ? $resetPoint : $start;
                $segEnd = $end;
                if ($segEnd->gt($segStart)) {
                    $segSeconds = $segEnd->diffInSeconds($segStart);

                    // Drive: cuenta solo cuando status === 'D'
                    if ($log->status === 'D') {
                        $driveAccum += $segSeconds;
                    }

                    // Shift: cuenta cuando status != 'OFF' AND status != 'SB'
                    if ($log->status !== 'OFF' && $log->status !== 'SB') {
                        $shiftAccum += $segSeconds;
                    }
                }
            }

            // --- Parte B: acumulación para cycle (rolling 7 días) ---
            // cycle debe PAUSARSE si status es OFF, SB o WT
            // por tanto, contamos solo cuando status NOT IN ['OFF','SB','WT']
            // y solo la intersección con [cycleWindowStart, now]
            $intStart = $start->lt($cycleWindowStart) ? $cycleWindowStart : $start;
            $intEnd = $end->gt($now) ? $now : $end;

            if ($intEnd->gt($intStart)) {
                if (!in_array($log->status, ['OFF', 'SB', 'WT'])) {
                    $cycleAccum += $intEnd->diffInSeconds($intStart);
                }
            }
        }

        // 5) Calcular remaining (no negativo)
        $driveRemaining = max(0, $H11 - $driveAccum);
        $shiftRemaining = max(0, $H14 - $shiftAccum);
        $cycleRemaining = max(0, $H70 - $cycleAccum);

        // 6) Obtener último log para estado actual
        $lastLog = $logs->last();
        $currentStatus = $lastLog ? $lastLog->status : 'OFF';

        // Devolver valores (enteros)
        return [
            'drive_remaining' => (int) $driveRemaining,
            'shift_remaining' => (int) $shiftRemaining,
            'cycle_remaining' => (int) $cycleRemaining,
            'current_status' => $currentStatus,
            'last_log' => $lastLog,
            'reset_point' => $resetPoint->toDateTimeString(),
            'cycle_window_start' => $cycleWindowStart->toDateTimeString(),
        ];
    }

    public function timers()
    {
        $driver = Auth::guard('driver')->user();
        $timers = $this->computeTimersForDriver($driver->id);
        return response()->json([
            'drive_remaining' => $timers['drive_remaining'],
            'shift_remaining' => $timers['shift_remaining'],
            'cycle_remaining' => $timers['cycle_remaining'],
            'current_status' => $timers['current_status'],
        ]);
    }

}

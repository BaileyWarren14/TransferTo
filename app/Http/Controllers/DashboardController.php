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

        return view('driver.dashboard', [
            'initialTimers' => $timers
        ]);
    }

   /* public function newindex()
    {
        $driver = Auth::guard('driver')->user();
        $timers = $this->computeTimersForDriver($driver->id);

        return view('driver.dashboard', [
            'serverTimers' => $timers
        ]);
    }*/


    public function timers()
    {
        $driver = Auth::guard('driver')->user();

        // Llamar la función que calcula los tiempos
        $data = $this->computeTimersForDriver($driver->id);

        // Retornar JSON a la vista
        return response()->json($data);
    }
    protected function computeTimersForDriver($driverId)
    {
        
         // Fecha y hora actual (sin cambiar zona horaria)
        $now = Carbon::now();

        // Inicio de semana (lunes)
        $weekStart = $now->copy()->startOfWeek(Carbon::MONDAY);

        // Obtener logs desde el inicio de semana hasta ahora
        $logs = dutystatuslog::where('driver_id', $driverId)
            ->whereBetween('changed_at', [$weekStart, $now])
            ->orderBy('changed_at', 'asc')
            ->get();

        // Si no hay logs, retornar todo en cero
        if ($logs->isEmpty()) {
            return [
                'D' => 0,
                'ON' => 0,
                'OFF' => 0,
                'SB' => 0,
                'WT' => 0,
                'total_logs' => 0,
                'week_start' => $weekStart->toDateTimeString(),
                'week_end' => $now->toDateTimeString(),
                'last_log' => null,
            ];
        }

        // Inicializar acumuladores de segundos
        $totals = [
            'D' => 0,
            'ON' => 0,
            'OFF' => 0,
            'SB' => 0,
            'WT' => 0,
        ];

        // Calcular segundos por estado
        foreach ($logs as $i => $log) {
            $start = Carbon::parse($log->changed_at);
            $end = ($i + 1 < $logs->count())
                ? Carbon::parse($logs[$i + 1]->changed_at)
                : $now; // hasta el momento actual si es el último

            if ($end->lt($start)) {
                continue; // ignorar si la diferencia es negativa
            }

            $diffSeconds = $end->diffInSeconds($start);
            $status = strtoupper($log->status);

            if (array_key_exists($status, $totals)) {
                $totals[$status] += $diffSeconds;
            }
        }

        $lastLog = $logs->last();

        // Retornar resultados
        return [
            'D' => $totals['D'],
            'ON' => $totals['ON'],
            'OFF' => $totals['OFF'],
            'SB' => $totals['SB'],
            'WT' => $totals['WT'],
            'total_logs' => $logs->count(),
            'week_start' => $weekStart->toDateTimeString(),
            'week_end' => $now->toDateTimeString(),
            'last_log' => $lastLog ? $lastLog->changed_at : null,
        ];

    }

/*
    public function timers()
    {
        $driverId = auth()->guard('driver')->id();
        $timers = $this->computeTimersForDriver($driverId);

        return response()->json([
            // ya vienen en segundos y ya clampados a los máximos
            'drive_remaining' => $timers['drive_remaining'],
            'shift_remaining' => $timers['shift_remaining'],
            'cycle_remaining' => $timers['cycle_remaining'],
            'current_status' => $timers['current_status'],
            // iso string del último log (útil para debug)
            'last_log' => $timers['last_log'] ? Carbon::parse($timers['last_log']->changed_at)->toIso8601String() : null,
            'reset_point' => $timers['reset_point'],
            'cycle_window_start' => $timers['cycle_window_start'],
        ]);
    }*/

}

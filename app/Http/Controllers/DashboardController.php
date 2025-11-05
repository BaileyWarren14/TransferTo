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
             'timers' => $timers
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

       // Método para obtener los timers vía AJAX
    public function timers()
    {
        $driver = Auth::guard('driver')->user();
        $timers = $this->computeTimersForDriver($driver->id);

        return response()->json($timers);
    }

    // Función interna para calcular los timers
    protected function computeTimersForDriver($driverId)
    {
        $estados = ['D', 'ON', 'OFF', 'SB', 'WT', 'PC', 'YM'];
        $now = Carbon::now('America/Mexico_City');
        $ahora = Carbon::now();

        $hoyTimes = array_fill_keys($estados, 0);
        $totalTimes = array_fill_keys($estados, 0);

        // 🔹 Traemos solo los logs recientes (última semana)
        $logs = \App\Models\DutyStatusLog::where('driver_id', $driverId)
            ->where('changed_at', '<=', $now->toDateTimeString())
            ->orderBy('changed_at', 'asc')
            ->get();

        if ($logs->isEmpty()) {
            return array_fill_keys([
                'DHoy','DTotal','ONHoy','ONTotal','SBHoy','SBTotal',
                'WTHoy','WTTotal','PCHoy','PCTotal','OFFHoy','OFFTotal',
                'YMHoy','YMTotal','DriveHoy','ShiftHoy','CycleHoy','CycleTotal'
            ], 0);
        }
        //dd($logs->toArray());


        $todayStart = Carbon::now('America/Mexico_City')->startOfDay();
        $todayEnd = Carbon::now('America/Mexico_City')->endOfDay();
        \Log::info('NOW:', [$now]);

        foreach ($logs as $index => $log) {
            $status = $log->status;

            // Convertir la fecha de UTC → America/Mexico_City
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $log->changed_at, 'America/Mexico_City');


            if ($index < count($logs) - 1) {
                $end = Carbon::createFromFormat('Y-m-d H:i:s', $logs[$index + 1]->changed_at, 'America/Mexico_City');

            } else {
                $end = Carbon::now('America/Mexico_City');
            }

            $diffSeconds = $start->diffInSeconds($end); // ya da positivo por defecto

            \Log::info('Comparando', [
                'status' => $status,
                'start' => $start->toDateTimeString(),
                'end' => $end->toDateTimeString(),
                'diff' => $diffSeconds,
            ]);

             //$diffSeconds = $end->diffInSeconds($start, false); 
           

            if (!in_array($status, $estados)) continue;

            $totalTimes[$status] += $diffSeconds;

            if ($start->between($todayStart, $todayEnd)) {
                $hoyTimes[$status] += $diffSeconds;
            }
        }

        // 🔹 Calcular derivados
        $DHoy = $hoyTimes['D'] ?? 0;
        $DTotal = $totalTimes['D'] ?? 0;
        $ONHoy = $hoyTimes['ON'] ?? 0;
        $ONTotal = $totalTimes['ON'] ?? 0;

        $DriveHoy = $DHoy;
        $ShiftHoy = $DHoy + $ONHoy;
        $CycleHoy = $ShiftHoy;
        $CycleTotal = $DTotal + $ONTotal;

        // 🔹 Evitar negativos (por cualquier ajuste)
        $normalize = fn($v) => max(0, round($v, 2));

        return [
            'DHoy' => $normalize($DHoy),
            'DTotal' => $normalize($DTotal),
            'ONHoy' => $normalize($ONHoy),
            'ONTotal' => $normalize($ONTotal),
            'SBHoy' => $normalize($hoyTimes['SB'] ?? 0),
            'SBTotal' => $normalize($totalTimes['SB'] ?? 0),
            'WTHoy' => $normalize($hoyTimes['WT'] ?? 0),
            'WTTotal' => $normalize($totalTimes['WT'] ?? 0),
            'PCHoy' => $normalize($hoyTimes['PC'] ?? 0),
            'PCTotal' => $normalize($totalTimes['PC'] ?? 0),
            'OFFHoy' => $normalize($hoyTimes['OFF'] ?? 0),
            'OFFTotal' => $normalize($totalTimes['OFF'] ?? 0),
            'YMHoy' => $normalize($hoyTimes['YM'] ?? 0),
            'YMTotal' => $normalize($totalTimes['YM'] ?? 0),
            'DriveHoy' => $normalize($DriveHoy),
            'ShiftHoy' => $normalize($ShiftHoy),
            'CycleHoy' => $normalize($CycleHoy),
            'CycleTotal' => $normalize($CycleTotal),
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

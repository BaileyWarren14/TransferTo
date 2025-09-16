<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;   // 👈
use App\Models\dutystatuslog; 
use Carbon\Carbon;

class LogbookController extends Controller
{
     public function index()
    {
        $driver = Auth::guard('driver')->user();

        // 🔹 Obtener los logs de hoy
        $todayLogs = dutystatuslog::where('driver_id', $driver->id)
            ->whereDate('changed_at', Carbon::today())
            ->orderBy('changed_at')
            ->get();

        // 🔹 Forzar inicio en OFF a las 00:00 si no hay log inicial
        if ($todayLogs->isEmpty() || Carbon::parse($todayLogs->first()->changed_at)->gt(Carbon::today())) {
            $fakeLog = new \stdClass();
            $fakeLog->status = 'OFF';
            $fakeLog->changed_at = Carbon::today()->toDateTimeString();
            $todayLogs->prepend($fakeLog);
        }

        // 🔹 Calcular horas activas desde que se quitó OFF
        $totalOnDutyMinutes = 0;
        $lastOffTime = null;

        foreach ($todayLogs as $log) {
            if ($log->status === 'OFF') {
                $lastOffTime = Carbon::parse($log->changed_at);
            } else {
                if ($lastOffTime) {
                    $totalOnDutyMinutes += Carbon::parse($log->changed_at)->diffInMinutes($lastOffTime);
                    $lastOffTime = null;
                }
            }
        }

        $totalOnDutyHours = intdiv($totalOnDutyMinutes, 60);
        $totalOnDutyMins = $totalOnDutyMinutes % 60;

        // 🔹 Preparar datos solo con los cambios reales
        $labels = [];
        $dutyStatuses = [];
        $yStatusMap = ['OFF'=>0, 'SB'=>1, 'D'=>2, 'ON'=>3, 'WT'=>4, 'PC'=>5, 'YM'=>6];

        foreach ($todayLogs as $log) {
            $labels[] = Carbon::parse($log->changed_at)->format('H:i');
            $dutyStatuses[] = $yStatusMap[$log->status];
        }

        // 🔹 Últimos 14 días (solo datos resumidos por día)
        $last14Days = dutystatuslog::where('driver_id', $driver->id)
            ->orderBy('changed_at', 'desc')
            ->get()
            ->groupBy(function ($log) {
                return Carbon::parse($log->changed_at)->format('Y-m-d');
            })->slice(0, 14);

        return view('driver.logs.show', compact(
            'todayLogs',
            'totalOnDutyHours',
            'totalOnDutyMins',
            'labels',
            'dutyStatuses',
            'last14Days'
        ));
    }
    //
      /*  public function index()
    {
        // Obtener logs del conductor (ajusta según tu modelo)
        $userId = auth()->id(); // o auth()->user()->id
        $logs = dutystatuslog::where('driver_id', $userId)
                    ->orderBy('changed_at', 'desc')
                    ->take(14)
                    ->get();

        // Calcular labels y dutyStatuses para la gráfica
        $labels = [];  
        $dutyStatuses = [];
        $statusMap = ['OFF'=>0,'SB'=>1,'D'=>2,'ON'=>3,'WT'=>4,'PC'=>5,'YM'=>6];

        foreach ($logs as $log) {
            $labels[] = \Carbon\Carbon::parse($log->changed_at)->format('H:i');
            $dutyStatuses[] = $statusMap[$log->status] ?? null;
        }

        // Total de horas ON DUTY
        $totalOnDutyHours = $logs->where('status', 'ON')->count() * 0.25; 
        // (asumiendo cada log = 15 min → 0.25 hrs, ajusta si es diferente)

        

        return view('driver.logs.show', compact('logs', 'labels', 'dutyStatuses', 'totalOnDutyHours'));
    }*/

    /*public function index()
    {
        $driver = Auth::guard('driver')->user();

        // Logs de hoy
        $todayLogs = DutyStatusLog::where('driver_id', $driver->id)
                        ->whereDate('changed_at', Carbon::today())
                        ->orderBy('changed_at', 'asc')
                        ->get();

        // Logs de los últimos 14 días (incluyendo hoy)
        $last14DaysLogs = DutyStatusLog::where('driver_id', $driver->id)
                            ->whereDate('changed_at', '>=', Carbon::today()->subDays(14))
                            ->orderBy('changed_at', 'asc')
                            ->get();

        // Preparar datos para la gráfica del día de hoy
        $labels = $todayLogs->map(function($log){
            return Carbon::parse($log->changed_at)->format('H:i');
        });

        $dutyStatuses = $todayLogs->map(function($log){
            $statusMap = ['OFF'=>0,'SB'=>1,'D'=>2,'ON'=>3,'WT'=>4,'PC'=>5,'YM'=>6];
            return $statusMap[$log->status] ?? 0;
        });

        // Total horas ON (si tienes columna 'hours', puedes sumar, aquí simplificado)
        $totalOnDutyHours = $todayLogs->where('status', 'ON')->count();

        return view('driver.logs.show', compact(
            'todayLogs',
            'last14DaysLogs',
            'labels',
            'dutyStatuses',
            'totalOnDutyHours'
        ));
    }*/

    public function today()
    {
        $driver = Auth::guard('driver')->user();

        // Ajusta según la zona horaria del driver, por ejemplo: -6
        $driverTimezone = '-6';

        $timezoneMap = [
            '-7' => 'America/Denver',
            '-6' => 'America/Mexico_City',
            '-5' => 'America/Bogota',
        ];

        $tz = $timezoneMap[$driverTimezone] ?? config('app.timezone');

        $startOfDay = Carbon::now($tz)->startOfDay(); 
        $endOfDay   = Carbon::now($tz)->endOfDay();

        // Obtener logs del día del driver en su zona horaria
        $todayLogs = DutyStatusLog::where('driver_id', $driver->id)
            ->whereBetween('changed_at', [
                $startOfDay->setTimezone('UTC'), 
                $endOfDay->setTimezone('UTC')
            ])
            ->orderBy('changed_at', 'asc')
            ->get();

        return view('driver.logs.today', compact('todayLogs'));
        }
    public function logstoday()
    {
         $driver = Auth::guard('driver')->user();

        // obtener los logs de hoy del driver
        $todayLogs = DutyStatusLog::where('driver_id', $driver->id)
            ->whereDate('changed_at', Carbon::today())
            ->orderBy('changed_at', 'asc')
            ->get();

        // calcular total de horas ON
        $totalOnDutyHours = 0;
        $onDutyLogs = $todayLogs->where('status', 'ON');

        if ($onDutyLogs->isNotEmpty()) {
            // primer registro ON
            $firstOnDuty = $onDutyLogs->first()->changed_at;
            // último registro del día
            $lastLog = $todayLogs->last()->changed_at ?? now();

            $totalOnDutyHours = Carbon::parse($firstOnDuty)->diffInHours(Carbon::parse($lastLog));
        }

    }
}

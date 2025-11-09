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
    

    // Forzar zona horaria
    date_default_timezone_set('America/Mexico_City');

    $now = Carbon::now(); // hora del servidor (ya México)
    $today = Carbon::today();
    
    // 🔹 Obtener logs de hoy sin convertir zona horaria
    $todayLogs = dutystatuslog::where('driver_id', $driver->id)
        ->whereBetween('changed_at', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])
        ->orderBy('changed_at')
        ->get();

    // 🔹 Si no hay log al inicio del día, agregar uno falso
    // if ($todayLogs->isEmpty() || Carbon::parse($todayLogs->first()->changed_at)->gt($today)) {
    //     $fakeLog = new \stdClass();
    //     $fakeLog->status = 'OFF';
    //     $fakeLog->changed_at = $today->toDateTimeString();
    //     $todayLogs->prepend($fakeLog);
    // }

    $yStatusMap = ['OFF'=>0, 'SB'=>1, 'D'=>2, 'ON'=>3, 'WT'=>4];

    // 🔹 Generar bloques de 1 minuto
    $labels = [];
    for ($h = 0; $h < 24; $h++) {
        $hour = ($h == 0) ? 'M' : (($h == 12) ? 'N' : ($h > 12 ? $h - 12 : $h));
        for ($m = 0; $m < 60; $m++) {
            $labels[] = $m == 0 ? $hour : '';
        }
    }

    $dutyStatuses = [];
    $start = $today->copy();
    $blocks = 1440; // 24 * 60
    $logIndex = 0;
    $lastStatus = 'OFF';

    for ($i = 0; $i < $blocks; $i++) {
        $time = $start->copy()->addMinutes($i);
        if ($time->gt($now)) break;

        while (isset($todayLogs[$logIndex]) && Carbon::parse($todayLogs[$logIndex]->changed_at)->lte($time)) {
            $lastStatus = $todayLogs[$logIndex]->status;
            $logIndex++;
        }

        $dutyStatuses[] = $yStatusMap[$lastStatus];
    }

    while (count($dutyStatuses) < $blocks) {
        $dutyStatuses[] = null;
    }

    // 🔹 Total de horas ON duty
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

    // 🔹 Últimos 14 días sin conversión
    $last14Days = dutystatuslog::where('driver_id', $driver->id)
        ->orderBy('changed_at', 'desc')
        ->get()
        ->groupBy(function($log) {
            return Carbon::parse($log->changed_at)->format('Y-m-d');
        })
        ->take(14);

    // 🔹 Logs "raw"
    $rawLogs = $todayLogs->map(function($log) {
        return [
            'status' => $log->status,
            'changed_at' => $log->changed_at, // tal como está en la BD
        ];
    });
    $driver = Auth::guard('driver')->user();
    $assignedTruck = $driver->truck; // Asumiendo relación 'truck' en el modelo Driver
    
    return view('driver.logs.show', compact(
        'assignedTruck',
        'labels',
        'dutyStatuses',
        'totalOnDutyHours',
        'totalOnDutyMins',
        'last14Days',
        'rawLogs'
    ));
}

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
    // Mostrar vista de detalles del logbook
    public function showDetail()
    {
        $driver = auth()->user();
        $tz = 'America/Mexico_City';

        $logs = dutystatuslog::where('driver_id', $driver->id)
            ->orderBy('changed_at', 'asc')
            ->get();

        $labels = [];
        $dutyStatuses = [];
        $yCategories = ['OFF','SB','D','ON','WT'];

        // Gráfica de 24h (minutos)
        for($i=0; $i<1440; $i++){
            $labels[] = Carbon::today($tz)->addMinutes($i)->format('H:i');
            $status = null;
            foreach($logs as $log){
                if(Carbon::parse($log->changed_at)->setTimezone($tz)->format('H:i') <= $labels[$i]){
                    $status = $yCategories[array_search($log->status, $yCategories)];
                }
            }
            $dutyStatuses[] = $status !== null ? $status : null;
        }

        return view('driver.logs.details_logbook', compact('logs','labels','dutyStatuses'));
    }

    // Editar un log específico
    public function update(Request $request, dutystatuslog $log)
    {
        $request->validate([
            'status' => 'required|in:ON,OFF,SB,D,WT,PC,YM',
            'notes' => 'nullable|string',
            'location' => 'nullable|string'
        ]);

        $log->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'location' => $request->location
        ]);

        return redirect()->route('driver.logs.details')
                         ->with('success', 'Log actualizado correctamente.');
    }

    // Eliminar un log
    public function destroy(dutystatuslog $log)
    {
        $log->delete();
        return redirect()->route('driver.logs.details')
                         ->with('success', 'Log eliminado correctamente.');
    }


    //para mostrar los logs del dia actual dentro del logbook
    public function showActivities($date)
    {
        $driver = Auth::guard('driver')->user();
        
        $tz = 'UTC';

        // Convertir $date en un objeto Carbon en la zona horaria correcta
        $day = Carbon::parse($date, $tz)->startOfDay();

        // Obtener logs del día seleccionado
        $logs = DutyStatusLog::where('driver_id', $driver->id)
            ->whereBetween('changed_at', [
                $day->copy()->setTimezone('UTC'),
                $day->copy()->endOfDay()->setTimezone('UTC')
            ])
            ->orderBy('changed_at', 'asc')
            ->get();

        // Transformar logs en actividades con duración
        $activities = [];
        foreach ($logs as $index => $log) {
            $start = Carbon::parse($log->changed_at)->setTimezone($tz);
            $end = isset($logs[$index + 1])
                ? Carbon::parse($logs[$index + 1]->changed_at)->setTimezone($tz)
                : $day->copy()->endOfDay();

            $activities[] = [
                'id' => $log->id,
                'status' => $log->status,
                'time' => $start->format('h:i:s A'),
                'duration' => $start->diff($end)->format('%Hh %Im'),
                'location' => $log->location ?? 'Unknown',
            ];
        }

        // Preparar datos para la gráfica
        $yCategories = ['OFF','SB','D','ON','WT'];
        $labels = [];
        $dutyStatuses = [];
        $logIndex = 0;
        $lastStatus = 'OFF';

        for ($i = 0; $i < 1440; $i++) {
            $minute = $day->copy()->addMinutes($i);

            // Etiquetas cada hora
            $labels = [];
            for ($h = 0; $h < 24; $h++) {
                $hour = ($h == 0) ? 'M' : (($h == 12) ? 'N' : ($h > 12 ? $h - 12 : $h));
                for ($m = 0; $m < 60; $m++) {
                    $labels[] = $m == 0 ? $hour : '';
                }
            }


            // Actualizar estado si hay un log en este minuto
            while (isset($logs[$logIndex]) && Carbon::parse($logs[$logIndex]->changed_at)->setTimezone($tz)->lte($minute)) {
                $lastStatus = $logs[$logIndex]->status;
                $logIndex++;
            }

            $dutyStatuses[] = array_search($lastStatus, $yCategories);
        }

        return view('driver.logs.activities', compact('activities', 'labels', 'dutyStatuses', 'date',));
    }

    public function latest()
    {
         $driver = Auth::guard('driver')->user();

        $lastLog = dutystatuslog::where('driver_id', $driver->id)
            ->orderBy('changed_at', 'desc')
            ->first();

        return response()->json($lastLog);
    }
     public function currentStatus()
    {
        $driver = Auth::guard('driver')->user();

        if (!$driver) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $lastLog = dutystatuslog::where('driver_id', $driver->id)
            ->orderBy('changed_at', 'desc')
            ->first();

        if (!$lastLog) {
            return response()->json([
                'status' => 'OFF',
                'changed_at' => null,
                'duration' => '0 min'
            ]);
        }

        // Calcular duración desde el último cambio
        $tz = 'America/Mexico_City';
        $changedAt = Carbon::parse($lastLog->changed_at)->setTimezone($tz);
        $now = Carbon::now($tz);
        $duration = $changedAt->diff($now)->format('%Hh %Im');

        return response()->json([
            'status' => $lastLog->status,
            'changed_at' => $changedAt->toDateTimeString(),
            'duration' => $duration
        ]);
    }
    public function getStatus()
    {
        // Simula obtención de datos reales (ajusta según tu estructura)
        $driverId = auth()->id();

        $latestLog = \App\Models\DutyStatusLog::where('driver_id', $driverId)
                        ->latest('changed_at')
                        ->first();

        if (!$latestLog) {
            return response()->json([
                'status' => 'OFF',
                'drive_time' => 0,
                'shift_time' => 0,
                'cycle_time' => 0,
            ]);
        }

        $timezone = 'America/Mexico_City';

        // Calcula tiempo desde el último cambio usando la zona horaria correcta
        $changedAt = Carbon::createFromFormat('Y-m-d H:i:s', $latestLog->changed_at, $timezone);
        $now = Carbon::now($timezone);

        $elapsedMinutes = $changedAt->diffInMinutes($now);
        \Log::info('NOW:', [$now]);
        \Log::info('changed at:', [$changedAt]);
        \Log::info('elapsedMinutes:', [$elapsedMinutes]);
        // Aquí podrías sumar tiempos reales de tu lógica de negocio
        return response()->json([
            'status' => $latestLog->status,
              // Ejemplo: minutos totales en Cycle
            'elapsed_minutes' => $elapsedMinutes
        ]);
    }

    /**
     * Guarda un nuevo cambio de estado
     */
    public function changeStatus(Request $request)
    {
        $driver = Auth::guard('driver')->user();

        $request->validate([
            'status' => 'required|in:ON,OFF,SB,D,WT,PC,YM',
        ]);

        $log = dutystatuslog::create([
            'driver_id' => $driver->id,
            'status' => $request->status,
            'changed_at' => now('UTC'),
        ]);

        return response()->json([
            'message' => 'Estado actualizado',
            'log' => $log
        ]);
    }

}

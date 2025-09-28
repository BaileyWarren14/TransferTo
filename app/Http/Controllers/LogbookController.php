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

        // 📌 Siempre trabajar con la zona horaria de México
        $tz = 'America/Mexico_City';
        $now = Carbon::now($tz);
        $today = Carbon::today($tz);

        // 🔹 Obtener logs de hoy en UTC pero convertirlos a hora local
        $todayLogs = dutystatuslog::where('driver_id', $driver->id)
            ->whereBetween('changed_at', [
                $today->copy()->startOfDay()->setTimezone('UTC'),
                $today->copy()->endOfDay()->setTimezone('UTC')
            ])
            ->orderBy('changed_at')
            ->get();
        // 🔹 Convertir todos los registros a hora local (UTC-6)
        $todayLogs->transform(function($log) use ($tz) {
            $log->changed_at = Carbon::parse($log->changed_at)->setTimezone($tz);
            return $log;
        });

        // 🔹 Si no hay log al inicio del día, agregar uno falso
        if ($todayLogs->isEmpty() || Carbon::parse($todayLogs->first()->changed_at)->gt($today)) {
            $fakeLog = new \stdClass();
            $fakeLog->status = 'OFF';
            $fakeLog->changed_at = $today->toDateTimeString();
            $todayLogs->prepend($fakeLog);
        }

        // 🔹 Mapear estados
        $yStatusMap = ['OFF'=>0, 'SB'=>1, 'D'=>2, 'ON'=>3, 'WT'=>4];

        // 🔹 Preparar etiquetas (96 bloques de 15 min)
        /*$labels = [];
        for ($h = 0; $h < 24; $h++) {
            $hour = ($h == 0) ? 'M' : (($h == 12) ? 'N' : ($h > 12 ? $h - 12 : $h));
            for ($i = 0; $i < 4; $i++) {
                $labels[] = $i == 0 ? $hour : '';
            }
        }

        // 🔹 Generar estados de los bloques de 15 min
        $dutyStatuses = [];
        $start = $today->copy();
        $now = Carbon::now($tz);   // hora actual en zona horaria correcta
        $blocks = 96;
        $logIndex = 0;
        $lastStatus = 'OFF';

        for ($i = 0; $i < $blocks; $i++) {
            $time = $start->copy()->addMinutes(15 * $i);

            // 🚫 Si el bloque está en el futuro, ya no seguimos
            if ($time->gt($now)) {
                break;
            }

            // ✅ Consumir todos los logs que cayeron hasta este bloque
            while (isset($todayLogs[$logIndex]) && Carbon::parse($todayLogs[$logIndex]->changed_at)->lte($time)) {
                $lastStatus = $todayLogs[$logIndex]->status;
                $logIndex++;
            }

            // Guardar el estado para este bloque
            $dutyStatuses[] = $yStatusMap[$lastStatus];
        }

        // 🔹 Si quieres forzar a que siempre sean 96 valores (obligatorio en tu caso)
        while (count($dutyStatuses) < $blocks) {
            $dutyStatuses[] = $yStatusMap[$lastStatus]; // repetir último estado
        }
        */
        // 🔹 Preparar etiquetas (1440 bloques de 1 min)
        $labels = [];
        for ($h = 0; $h < 24; $h++) {
            $hour = ($h == 0) ? 'M' : (($h == 12) ? 'N' : ($h > 12 ? $h - 12 : $h));
            for ($m = 0; $m < 60; $m++) {
                 
           
                $labels[] = $m == 0 ? $hour : '';
           
            }
        }

        // 🔹 Generar estados de los bloques de 1 min
        $dutyStatuses = [];
        $start = $today->copy();
        $now = Carbon::now($tz);
        $blocks = 1440; // 24 * 60
        $logIndex = 0;
        $lastStatus = 'OFF';

        for ($i = 0; $i < $blocks; $i++) {
            $time = $start->copy()->addMinutes($i);

             if ($time->gt($now)) break;

            // ✅ Verificar si algún log cambió antes o en este minuto
            while (isset($todayLogs[$logIndex]) && Carbon::parse($todayLogs[$logIndex]->changed_at)->lte($time)) {
                $lastStatus = $todayLogs[$logIndex]->status;
                $logIndex++;
            }

            $dutyStatuses[] = $yStatusMap[$lastStatus];
        }

        // 🔹 Si quieres forzar 1440 valores
        while (count($dutyStatuses) < $blocks) {
            $dutyStatuses[] = null;
        }


        // 🔹 Calcular total de horas ON duty
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
        
        // 🔹 Últimos 14 días (también ajustados a MX)
        $last14Days = dutystatuslog::where('driver_id', $driver->id)
            ->orderBy('changed_at', 'desc')
            ->get()
            ->groupBy(function($log) use ($tz) {
                return Carbon::parse($log->changed_at)->setTimezone($tz)->format('Y-m-d');
            })
            ->take(14);

        // 🔹 Logs "raw" para debug
        $rawLogs = $todayLogs->map(function($log) {
            return [
                'status' => $log->status,
                'changed_at' => $log->changed_at, // ya en UTC-6
            ];
        });

        return view('driver.logs.show', compact(
            'labels',
            'dutyStatuses',
            'totalOnDutyHours',
            'totalOnDutyMins',
            'last14Days',
            'rawLogs'
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

}

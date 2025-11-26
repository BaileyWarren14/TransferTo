<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;   // 👈
use App\Models\dutystatuslog; 
use App\Models\Fuel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewMessageMail;

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

    public function generateLogbookPDF(Request $request)
    {
        $driver = Auth::guard('driver')->user();

        // Obtener últimos 8 días
        $dates = \App\Models\DutyStatusLog::where('driver_id', $driver->id)
            ->selectRaw('DATE(changed_at) as day')
            ->distinct()
            ->orderBy('day', 'desc')
            ->take(8)
            ->pluck('day');

        $daysData = [];

        foreach ($dates as $date) {
            // Logs del día
            $logs = \App\Models\DutyStatusLog::where('driver_id', $driver->id)
                ->whereDate('changed_at', $date)
                ->orderBy('changed_at', 'asc')
                ->get();

            // Buscar work order de ese día
            $workOrder = Fuel::where('driver_id', $driver->id)
                ->whereDate('created_at', $date)
                ->first();

            $daysData[] = [
                'date' => Carbon::parse($date)->format('F d, Y'),
                'logs' => $logs,
                'distance' => $workOrder->distance ?? '',
                'plate' => $workOrder->truck->plate ?? '',
                'trailer' => $workOrder->trailer_number ?? '',
            ];
        }

        // Renderizar PDF
        $pdf = Pdf::loadView('driver.logs.pdf_logbook', [
            'driver' => $driver,
            'daysData' => $daysData
        ])->setPaper('letter', 'portrait');

        if ($request->get('action') === 'download') {
            return $pdf->download('Driver_Logbook_' . now()->format('Ymd') . '.pdf');
        } else {
            // Acción de compartir por correo
            $email = $request->get('email');
            \Mail::send('emails.logbook_share', ['driver' => $driver], function ($message) use ($email, $pdf) {
                $message->to($email)
                    ->subject('Driver Logbook PDF')
                    ->attachData($pdf->output(), 'Driver_Logbook.pdf');
            });

            return back()->with('success', 'Logbook enviado correctamente a ' . $email);
        }
    }
     public function sendLogbook(Request $request, $type, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'client_time' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $senderType = Auth::user() instanceof \App\Models\Driver ? 'driver' : 'admin';

        $driver = Auth::guard('driver')->user();

        // =============================
        //   OBTENER DATOS DEL LOGBOOK
        // =============================
        $dates = DutyStatusLog::where('driver_id', $driver->id)
            ->selectRaw('DATE(changed_at) as day')
            ->distinct()
            ->orderBy('day', 'desc')
            ->take(8)
            ->pluck('day');

        $daysData = [];

        foreach ($dates as $date) {
            $logs = DutyStatusLog::where('driver_id', $driver->id)
                ->whereDate('changed_at', $date)
                ->orderBy('changed_at')
                ->get();

            $workOrder = Fuel::where('driver_id', $driver->id)
                ->whereDate('created_at', $date)
                ->first();

            $daysData[] = [
                'date' => Carbon::parse($date)->format('F d, Y'),
                'logs' => $logs,
                'distance' => $workOrder->distance ?? '',
                'plate' => $workOrder->truck->plate ?? '',
                'trailer' => $workOrder->trailer_number ?? '',
            ];
        }

        // =============================
        //   GENERAR PDF
        // =============================
        $pdf = Pdf::loadView('driver.logs.pdf_logbook', [
            'driver'    => $driver,
            'daysData'  => $daysData,
        ]);

        $fileName = 'Driver_Logbook_' . now()->format('Ymd') . '.pdf';

        // Guardar temporalmente
        $pdfPath = storage_path('app/' . $fileName);
        $pdf->save($pdfPath);

        // =============================
        //   ENVIAR CORREO
        // =============================
        Mail::send('emails.logbook_share', [
            'driver' => $driver
        ], function ($message) use ($request, $pdfPath, $fileName) {
            $message->to($request->email)
                    ->subject('Shared Driver Logbook')
                    ->attach($pdfPath, [
                        'as' => $fileName,
                        'mime' => 'application/pdf',
                    ]);
        });

        // =============================
        //  CREAR NOTIFICACIÓN
        // =============================
        Notification::create([
            'user_id' => $id,
            'type'    => 'logbook',
            'title'   => 'Nuevo Logbook compartido',
            'message' => 'Has recibido un logbook de ' . Auth::user()->name,
            'read_at' => null,
        ]);

        return response()->json(['success' => true]);
    }

     private function buildLogbookPDF($driver)
    {
        // Obtener últimos 8 días
        $dates = DutyStatusLog::where('driver_id', $driver->id)
            ->selectRaw('DATE(changed_at) as day')
            ->distinct()
            ->orderBy('day', 'desc')
            ->take(8)
            ->pluck('day');

        $daysData = [];

        foreach ($dates as $date) {
            $logs = DutyStatusLog::where('driver_id', $driver->id)
                ->whereDate('changed_at', $date)
                ->orderBy('changed_at', 'asc')
                ->get();

            $workOrder = Fuel::where('driver_id', $driver->id)
                ->whereDate('created_at', $date)
                ->first();

            $daysData[] = [
                'date' => Carbon::parse($date)->format('F d, Y'),
                'logs' => $logs,
                'distance' => $workOrder->distance ?? '',
                'plate' => $workOrder->truck->plate ?? '',
                'trailer' => $workOrder->trailer_number ?? '',
            ];
        }

        // Render PDF
        return Pdf::loadView('driver.logs.pdf_logbook', [
            'driver' => $driver,
            'daysData' => $daysData
        ])->setPaper('letter', 'portrait');
    }


    /**
     * 2. Descargar el PDF
     */
    public function downloadLogbookPDF()
    {
        $driver = Auth::guard('driver')->user();

        $pdf = $this->buildLogbookPDF($driver);

        return $pdf->download('Driver_Logbook_' . now()->format('Ymd') . '.pdf');
    }


    /**
     * 3. Enviar el PDF por correo
     */
    public function emailLogbookPDF(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $driver = Auth::guard('driver')->user();

        // Generar PDF usando la función 1
        $pdf = $this->buildLogbookPDF($driver);

        // Renderizar el PDF en binario
        $pdfContent = $pdf->output();
        
        // Enviar correo con adjunto
        Mail::send('emails.logbook_share', ['driver' => $driver], function ($message) use ($request, $pdfContent) {
            $message->to($request->email)
                ->subject('Driver Logbook')
                ->attachData($pdfContent, 'Driver_Logbook.pdf');
        });

        return response()->json(['success' => true, 'message' => 'Logbook enviado correctamente']);
    }
    private function generateDailyGraphSVG($logs, $date, $driverId)
    {
        $width = 1400;
        $height = 200;

        // Mapeo vertical
        $yMap = [
            'OFF' => 10,
            'SB'  => 50,
            'D'   => 90,
            'ON'  => 130,
            'WT'  => 170,
        ];

        // Crear bloques de 1 minuto (1440 puntos)
        $minuteStatus = array_fill(0, 1440, 'OFF');

        foreach ($logs as $log) {
            $start = Carbon\Carbon::parse($log->changed_at)->minutesSinceMidnight();
            $status = strtoupper($log->status);

            for ($i = $start; $i < 1440; $i++) {
                $minuteStatus[$i] = $status;
            }
        }

        // Crear SVG
        $svg = "<svg xmlns='http://www.w3.org/2000/svg' width='{$width}' height='{$height}' style='background:#fff;'>";

        // Líneas horizontales (OFF, SB, D, ON, WT)
        foreach ($yMap as $label => $y) {
            $svg .= "<line x1='0' y1='{$y}' x2='{$width}' y2='{$y}' stroke='#ccc'/>";
            $svg .= "<text x='5' y='".($y - 2)."' font-size='10'>{$label}</text>";
        }

        // Dibujar la gráfica (línea continua)
        $xStep = $width / 1440;
        $points = "";

        for ($i = 0; $i < 1440; $i++) {
            $x = $i * $xStep;
            $status = $minuteStatus[$i];
            $y = $yMap[$status] ?? 10;
            $points .= "{$x},{$y} ";
        }

        $svg .= "<polyline points='{$points}' fill='none' stroke='blue' stroke-width='2'/>";

        $svg .= "</svg>";

        // Guardar archivo
        $path = "logbook_graphs/graph_{$driverId}_{$date}.svg";

        Storage::disk('local')->put($path, $svg);

        return storage_path("app/{$path}");
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;   // 👈
use App\Models\dutystatuslog; 
use Carbon\Carbon;

class LogbookController extends Controller
{
    //
    public function show(Logbook $logbook)
    {
        // Calcular total de horas ON
        $logbook->total_on_duty_hours = DutyStatus::where('status', 'ON')
            ->whereDate('created_at', $logbook->date)
            ->sum('hours'); // ajusta según tu columna

        // Verificar si hubo inspección
        $logbook->inspection = Inspection::whereDate('created_at', $logbook->date)->exists();

        // Preparar datos para el chart
        $labels = []; // etiquetas X (ej: cada hora o cada 15 min)
        $duty_statuses = []; // array de estados numericos o mapeados para el chart

        // Ejemplo simplificado: obtener duty statuses del día
        $statuses = DutyStatus::whereDate('created_at', $logbook->date)
            ->orderBy('created_at')->get();

        foreach ($statuses as $s) {
            $labels[] = $s->created_at->format('H:i');
            $duty_statuses[] = match($s->status) {
                'OFF' => 0,
                'SB' => 1,
                'D' => 2,
                'ON' => 3,
                'WT' => 4,
                default => null,
            };
        }

        // Si quieres rellenar los espacios de tiempo que no tienen datos:
        // (opcional)

        return view('logbook.show', [
            'logbook' => $logbook,
            'labels' => $labels,
            'logbook' => $logbook,
            'logbook->duty_statuses' => $duty_statuses,
        ]);
    }
    public function today()
        {
            $driver = Auth::guard('driver')->user();

            $logs = dutystatuslog::where('driver_id', $driver->id)
                ->whereDate('created_at', Carbon::today())
                ->orderBy('created_at', 'asc')
                ->get();

            return view('driver.logs.today', compact('logs'));
        }
    
}

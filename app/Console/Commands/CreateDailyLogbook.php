<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Logbook;
use App\Models\DutyStatus;
use App\Models\Inspection;
use Carbon\Carbon;

class CreateDailyLogbook extends Command
{
    protected $signature = 'logbook:create-daily';
    protected $description = 'Crea un registro diario del logbook';

    public function handle()
    {
        $today = Carbon::today();

        // Evitar duplicados
        if (Logbook::where('date', $today)->exists()) {
            $this->info("El logbook de hoy ya existe.");
            return;
        }

        // Total horas ON DUTY
        $totalOnDuty = DutyStatus::whereDate('created_at', $today)
            ->where('status', 'ON')
            ->sum('hours'); // o calcula según tu modelo

        // Si hubo inspección
        $hadInspection = Inspection::whereDate('created_at', $today)->exists();

        // Duty statuses para chart (puede ser por cada 15 min)
        $statuses = DutyStatus::whereDate('created_at', $today)
            ->orderBy('created_at')
            ->pluck('status'); // array de estados

        Logbook::create([
            'date' => $today,
            'total_on_duty_hours' => $totalOnDuty,
            'inspection' => $hadInspection,
            'duty_statuses' => $statuses,
        ]);

        $this->info("Logbook de hoy creado correctamente.");
    }
}

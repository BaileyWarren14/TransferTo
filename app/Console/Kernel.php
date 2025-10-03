<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define la programación de comandos.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('check:driver-inspections')->everyMinute();
        // Aquí agregas tus tareas, ejemplo:
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
        $today = now()->toDateString();

        if (!\App\Models\Logbook::where('date', $today)->exists()) {
            \App\Models\Logbook::create([
                'date' => $today,
                'total_on_duty_hours' => 0,
                'inspection' => false,
                'duty_statuses' => json_encode([]),
            ]);
        }
        })->dailyAt('00:00'); // ejecuta cada día a medianoche
         $schedule->command('logbook:create-daily')->dailyAt('00:00');
    }

    /**
     * Registra los comandos para tu aplicación.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    public function showToday()
    {
        $logbook = Logbook::whereDate('date', today())->firstOrFail();

        // Labels del gráfico, por ejemplo cada hora
        $labels = [];
        for ($i = 0; $i < 24; $i++) {
            $labels[] = $i . ':00';
        }

        return view('logbook.show', compact('logbook', 'labels'));
    }
}

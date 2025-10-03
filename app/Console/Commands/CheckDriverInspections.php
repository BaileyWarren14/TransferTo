<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckDriverInspections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-driver-inspections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
            //
            $fiveMinutesAgo = now()->subMinutes(5);

        $drivers = Driver::where('state', 'drive')
            ->whereDoesntHave('inspections', function($q) use ($fiveMinutesAgo) {
                $q->whereDate('created_at', now()->toDateString());
            })
            ->get();

        foreach($drivers as $driver) {
            // Verificar si ya se alertó hoy
            $alertExists = DriverAlert::where('driver_id', $driver->id)
                ->where('type', 'missing_inspection')
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if(!$alertExists) {
                $message = "No realizó la inspección tras cambiar a 'drive'.";

                // Guardar alerta en DB
                DriverAlert::create([
                    'driver_id' => $driver->id,
                    'type' => 'missing_inspection',
                    'message' => $message,
                    'alerted_at' => now(),
                ]);

                // Enviar notificación interna
                \App\Models\Notification::create([
                    'user_id' => $driver->id,
                    'type' => 'safety',
                    'title' => 'Falta de inspección',
                    'message' => $message,
                ]);

                // Enviar notificación al admin
                $admins = Admin::all();
                foreach($admins as $admin) {
                    \App\Models\Notification::create([
                        'user_id' => $admin->id,
                        'type' => 'safety',
                        'title' => 'Falta de inspección',
                        'message' => "El driver {$driver->name} no realizó inspección.",
                    ]);
                }

                // Enviar correo al driver y admins
                foreach($admins as $admin) {
                    \Mail::to($admin->email)->send(new \App\Mail\DriverAlertMail($driver, $message));
                }
                \Mail::to($driver->email)->send(new \App\Mail\DriverAlertMail($driver, $message));
            }
        }
    }
}

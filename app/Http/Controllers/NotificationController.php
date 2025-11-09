<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\dutystatuslog;


class NotificationController extends Controller
{
    //
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
        ->whereNull('read_at') // solo no leídas
        ->orderBy('created_at', 'desc')
        ->get();

        return view('driver.notifications.index_notifications', compact('notifications'));
    }

    public function markRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();
        $notification->update(['read_at' => now()]);
        return redirect()->back();
    }

    public function json()
    {
        $notifications = Notification::where('user_id', Auth::id())
        ->whereNull('read_at') // solo no leídas
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json($notifications);
    }

    public function hosViolations()
    {
        $driverId = Auth::guard('driver')->id();
        $today = Carbon::today('America/Mexico_City');

        
         // Obtener TODOS los logs del conductor
        $logs = DutyStatusLog::where('driver_id', $driverId)
            ->orderBy('changed_at', 'asc')
            ->get();
        $violations = [];

        // Variables de control
        $totalDrive = 0;
        $totalOnDuty = 0;
        $currentDriveStreak = 0;
        $lastStatus = null;
        $lastTime = null;

        foreach ($logs as $log) {
            if ($lastTime) {
                $duration = Carbon::parse($lastTime)->diffInMinutes(Carbon::parse($log->changed_at));

                // Acumular tiempos
                if (in_array($lastStatus, ['D', 'ON'])) $totalOnDuty += $duration;
                if ($lastStatus === 'D') {
                    $totalDrive += $duration;
                    $currentDriveStreak += $duration;
                } else {
                    $currentDriveStreak = 0; // reinicia si cambia el estado
                }

                // Detectar violaciones
                if ($currentDriveStreak > 8 * 60 && !collect($violations)->contains('type', '8hr_drive')) {
                    $violations[] = [
                        'type' => '8hr_drive',
                        'date' => $today->format('M d'),
                        'message' => 'Violation Alert — 8 Hours continuous driving',
                    ];
                }
                if ($totalDrive > 11 * 60 && !collect($violations)->contains('type', '11hr_drive')) {
                    $violations[] = [
                        'type' => '11hr_drive',
                        'date' => $today->format('M d'),
                        'message' => 'Violation Alert — More than 11 hours driving time expired',
                    ];
                }
                if ($totalOnDuty > 14 * 60 && !collect($violations)->contains('type', '14hr_on')) {
                    $violations[] = [
                        'type' => '14hr_on',
                        'date' => $today->format('M d'),
                        'message' => 'Violation Alert — More than 14 hours on duty time expired',
                    ];
                }
            }

            $lastStatus = $log->status;
            $lastTime = $log->changed_at;
        }

        return view('driver.notifications.hos_alerts', compact('violations'));
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\dutystatuslog;
use App\Models\Driver;



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

        $dates = \App\Models\DutyStatusLog::where('driver_id', $driverId)
            ->selectRaw('DATE(changed_at) as day')
            ->distinct()
            ->orderBy('day', 'desc')
            ->pluck('day');

        $violations = [];

        foreach ($dates as $day) {
            $logs = \App\Models\DutyStatusLog::where('driver_id', $driverId)
                ->whereDate('changed_at', $day)
                ->orderBy('changed_at', 'asc')
                ->get();

            if ($logs->isEmpty()) continue;

            $totalDrive = 0;
            $totalOnDuty = 0;
            $currentDriveStreak = 0;
            $lastStatus = null;
            $lastTime = null;
            $didInspection = false;
            $driveStartTime = null;

            foreach ($logs as $log) {
                $status = strtoupper($log->status);
                $logTime = Carbon::parse($log->changed_at);

                // Detectar inspección
                if ((!empty($log->notes) && stripos($log->notes, 'inspection') !== false) || $status === 'INSP') {
                    $didInspection = true;
                }

                if ($lastTime) {
                    $duration = Carbon::parse($lastTime)->diffInMinutes($logTime);

                    if (in_array($lastStatus, ['D', 'ON'])) {
                        $totalOnDuty += $duration;
                    }

                    if ($lastStatus === 'D') {
                        $totalDrive += $duration;
                        $currentDriveStreak += $duration;
                    } else {
                        $currentDriveStreak = 0;
                    }

                    // === HOS Violations ===
                    if ($currentDriveStreak > 8 * 60) {
                        $violations[] = [
                            'type' => uniqid('8hr_drive_'),
                            'category' => 'HOS',
                            'date' => $day,
                            'message' => 'Violation Alert — 8 Hours continuous driving',
                        ];
                    }

                    if ($totalDrive > 11 * 60) {
                        $violations[] = [
                            'type' => uniqid('11hr_drive_'),
                            'category' => 'HOS',
                            'date' => $day,
                            'message' => 'Violation Alert — More than 11 hours driving time expired',
                        ];
                    }

                    if ($totalOnDuty > 14 * 60) {
                        $violations[] = [
                            'type' => uniqid('14hr_on_'),
                            'category' => 'HOS',
                            'date' => $day,
                            'message' => 'Violation Alert — More than 14 hours on duty time expired',
                        ];
                    }
                }

                // === DOT Inspection ===
                if ($status === 'D') {
                    if (!$driveStartTime) {
                        $driveStartTime = $logTime;
                        $didInspection = false;
                    }
                }

                if ($driveStartTime && !$didInspection) {
                    $elapsed = $driveStartTime->diffInMinutes($logTime);
                    if ($elapsed > 15) {
                        $violations[] = [
                            'type' => uniqid('no_insp_'),
                            'category' => 'DOT Inspection',
                            'date' => $day,
                            'message' => 'Violation Alert — More than 15 minutes without doing inspection',
                        ];
                        $didInspection = true;
                    }
                }

                $lastStatus = $status;
                $lastTime = $logTime;
            }
        }

        // === Ordenar por fecha completa (desc) ===
        usort($violations, function ($a, $b) {
            return strtotime($b['date']) <=> strtotime($a['date']);
        });

        // Convertir la fecha a formato visible
        foreach ($violations as &$v) {
            $v['display_date'] = Carbon::parse($v['date'])->format('M d');
        }

        return view('driver.notifications.hos_alerts', compact('violations'));
    }


    //Funcion para los admins obtener todos los reportes de drivers
    public function driversReports()
    {
        $drivers = Driver::all(); // Obtener todos los drivers
        $allViolations = [];

        foreach ($drivers as $driver) {
            $dates = DutyStatusLog::where('driver_id', $driver->id)
                ->selectRaw('DATE(changed_at) as day')
                ->distinct()
                ->orderBy('day', 'desc')
                ->pluck('day');

            $violations = [];

            foreach ($dates as $day) {
                $logs = DutyStatusLog::where('driver_id', $driver->id)
                    ->whereDate('changed_at', $day)
                    ->orderBy('changed_at', 'asc')
                    ->get();

                if ($logs->isEmpty()) continue;

                $totalDrive = 0;
                $totalOnDuty = 0;
                $currentDriveStreak = 0;
                $lastStatus = null;
                $lastTime = null;
                $didInspection = false;
                $driveStartTime = null;

                foreach ($logs as $log) {
                    $status = strtoupper($log->status);
                    $logTime = Carbon::parse($log->changed_at);

                    if ((!empty($log->notes) && stripos($log->notes, 'inspection') !== false) || $status === 'INSP') {
                        $didInspection = true;
                    }

                    if ($lastTime) {
                        $duration = Carbon::parse($lastTime)->diffInMinutes($logTime);

                        if (in_array($lastStatus, ['D', 'ON'])) {
                            $totalOnDuty += $duration;
                        }

                        if ($lastStatus === 'D') {
                            $totalDrive += $duration;
                            $currentDriveStreak += $duration;
                        } else {
                            $currentDriveStreak = 0;
                        }

                        if ($currentDriveStreak > 8 * 60) {
                            $violations[] = [
                                'driver' => $driver->name . ' ' . $driver->lastname,
                                'type' => uniqid('8hr_drive_'),
                                'category' => 'HOS',
                                'date' => $day,
                                'message' => 'Violation Alert — 8 Hours continuous driving',
                            ];
                        }

                        if ($totalDrive > 11 * 60) {
                            $violations[] = [
                                'driver' => $driver->name . ' ' . $driver->lastname,
                                'type' => uniqid('11hr_drive_'),
                                'category' => 'HOS',
                                'date' => $day,
                                'message' => 'Violation Alert — More than 11 hours driving time expired',
                            ];
                        }

                        if ($totalOnDuty > 14 * 60) {
                            $violations[] = [
                                'driver' => $driver->name . ' ' . $driver->lastname,
                                'type' => uniqid('14hr_on_'),
                                'category' => 'HOS',
                                'date' => $day,
                                'message' => 'Violation Alert — More than 14 hours on duty time expired',
                            ];
                        }
                    }

                    if ($status === 'D') {
                        if (!$driveStartTime) {
                            $driveStartTime = $logTime;
                            $didInspection = false;
                        }
                    }

                    if ($driveStartTime && !$didInspection) {
                        $elapsed = $driveStartTime->diffInMinutes($logTime);
                        if ($elapsed > 15) {
                            $violations[] = [
                                'driver' => $driver->name . ' ' . $driver->lastname,
                                'type' => uniqid('no_insp_'),
                                'category' => 'DOT Inspection',
                                'date' => $day,
                                'message' => 'Violation Alert — More than 15 minutes without doing inspection',
                            ];
                            $didInspection = true;
                        }
                    }

                    $lastStatus = $status;
                    $lastTime = $logTime;
                }
            }

            // Ordenar por fecha descendente
            usort($violations, function ($a, $b) {
                return strtotime($b['date']) <=> strtotime($a['date']);
            });

            // Formatear fechas
            foreach ($violations as &$v) {
                $v['display_date'] = Carbon::parse($v['date'])->format('M d');
            }

            $allViolations = array_merge($allViolations, $violations);
        }

        return view('admin.reports.reports', compact('allViolations'));
    }


}

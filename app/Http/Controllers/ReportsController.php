<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\dutystatuslog;
use Carbon\Carbon;

class ReportsController extends Controller
{
    private function getAllViolations()
    {
        $drivers = Driver::all();
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
                        $duration = $lastTime->diffInMinutes($logTime);

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
                            $violations[] = $this->violation($driver, $day, 'HOS',
                                'Violation Alert — 8 Hours continuous driving'
                            );
                        }

                        if ($totalDrive > 11 * 60) {
                            $violations[] = $this->violation($driver, $day, 'HOS',
                                'Violation Alert — More than 11 hours driving time expired'
                            );
                        }

                        if ($totalOnDuty > 14 * 60) {
                            $violations[] = $this->violation($driver, $day, 'HOS',
                                'Violation Alert — More than 14 hours on duty time expired'
                            );
                        }
                    }

                    if ($status === 'D' && !$driveStartTime) {
                        $driveStartTime = $logTime;
                        $didInspection = false;
                    }

                    if ($driveStartTime && !$didInspection) {
                        if ($driveStartTime->diffInMinutes($logTime) > 15) {
                            $violations[] = $this->violation($driver, $day, 'DOT Inspection',
                                'Violation Alert — More than 15 minutes without doing inspection'
                            );
                            $didInspection = true;
                        }
                    }

                    $lastStatus = $status;
                    $lastTime = $logTime;
                }
            }

            foreach ($violations as &$v) {
                $v['display_date'] = Carbon::parse($v['date'])->format('M d');
            }

            $allViolations = array_merge($allViolations, $violations);
        }

        usort($allViolations, fn ($a, $b) =>
            strtotime($b['date']) <=> strtotime($a['date'])
        );

        return $allViolations;
    }

    /**
     * Helper para crear violaciones
     */
    private function violation($driver, $day, $category, $message)
    {
        return [
            'driver' => $driver->name . ' ' . $driver->lastname,
            'category' => $category,
            'date' => $day,
            'message' => $message,
        ];
    }

    /**
     * ===============================
     * VISTA NORMAL (sin filtros)
     * ===============================
     */
    public function driversReports()
    {
        $allViolations = $this->getAllViolations();

        $categories = collect($allViolations)
            ->pluck('category')
            ->unique()
            ->values();

        return view('admin.reports.reports', compact('allViolations', 'categories'));
    }

    /**
     * ===============================
     * VISTA CON FILTROS
     * ===============================
     */
    public function filterDriversReports(Request $request)
    {
        $allViolations = $this->getAllViolations();

        $filtered = collect($allViolations);

        if ($request->filled('driver')) {
            $filtered = $filtered->filter(fn ($v) =>
                str_contains(strtolower($v['driver']), strtolower($request->driver))
            );
        }

        if ($request->filled('category')) {
            $filtered = $filtered->where('category', $request->category);
        }

        if ($request->filled('from')) {
            $filtered = $filtered->filter(fn ($v) =>
                Carbon::parse($v['date'])->gte($request->from)
            );
        }

        if ($request->filled('to')) {
            $filtered = $filtered->filter(fn ($v) =>
                Carbon::parse($v['date'])->lte($request->to)
            );
        }

        $categories = collect($allViolations)
            ->pluck('category')
            ->unique()
            ->values();

        return view(
            'admin.reports.reports',
            [
                'allViolations' => $filtered->values(),
                'categories' => $categories
            ]
        );
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dutystatuslog; 

class DashboardController extends Controller
{
    //
    public function index()
    {
        $driver = Auth::guard('driver')->user();

        $lastLog = dutystatuslog::where('driver_id', $driver->id)
            ->orderBy('changed_at', 'desc')
            ->first();

        $currentStatus = $lastLog ? $lastLog->status : 'OFF';

        return view('driver.dashboard', compact('currentStatus','lastLog'));
    }

}

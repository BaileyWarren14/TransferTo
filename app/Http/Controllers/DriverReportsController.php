<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverReportsController extends Controller
{
    //
    public function index()
    {
        $alerts = DriverAlert::with('driver')->orderBy('created_at', 'desc')->get();
        return view('admin.drivers.reports', compact('alerts'));
    }
}

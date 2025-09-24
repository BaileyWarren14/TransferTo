<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TimezoneController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'timezone' => 'required|string'
        ]);

        Session::put('timezone', $request->timezone);

        return response()->json(['success' => true]);
    }
}

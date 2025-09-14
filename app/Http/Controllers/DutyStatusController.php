<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DutyStatusController extends Controller
{
    //
    public function create()
    {
        return view('driver.duty_status.change_duty_status');
    }

    public function store(Request $request)
    {
        // Aquí validas y guardas en BD lo que se envía desde la vista
        $data = $request->validate([
            'duty_status' => 'required|string',
            'location' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Ejemplo: guardar en la tabla duty_status_logs
        // DutyStatus::create($data);

        return redirect()->route('driver.dashboard')
                         ->with('success', 'Duty status actualizado correctamente.');
    }
}

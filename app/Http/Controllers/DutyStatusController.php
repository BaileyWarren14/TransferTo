<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\dutystatuslog; // Modelo para la tabla donde guardas logs
use App\Models\Driver;        // Modelo del driver
use Carbon\Carbon;

class DutyStatusController extends Controller
{
    //
   // Mostrar formulario para cambiar Duty Status
    // Mostrar formulario para cambiar Duty Status
    public function create()
    {
        // Obtener el driver autenticado usando el guard 'driver'
        $driver = Auth::guard('driver')->user();

        return view('driver.duty_status.change_duty_status', compact('driver'));
    }

    // Guardar Duty Status
    public function store(Request $request)
    {
        
        // Validación de los datos
        $data = $request->validate([
            'status' => 'required|string|in:ON,OFF,SB,D,WT,PC,YM',
            'location' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Obtener driver autenticado
        $driver = Auth::guard('driver')->user();
        $changedAt = Carbon::now('UTC');
        // Guardar en la tabla de logs
        DutyStatusLog::create([
            'driver_id' => $driver->id,
            'status' => $data['status'],
            'location' => $data['location'],
            'notes' => $data['notes'] ?? null,
            'changed_at' => $changedAt,
        ]);

        // Actualizar el estado actual del driver
        $driver->status = $data['status'];
        $driver->save();

        return redirect()->route('driver.logs.today')
                         ->with('success', 'Duty status updated successfully and added to today\'s log.');
    }
    // Mostrar formulario de edición de un log
    public function edit($id)
    {
        $log = dutystatuslog::findOrFail($id);

        // Convertir hora a formato compatible con input datetime-local
        $log->changed_at = Carbon::parse($log->changed_at)->format('Y-m-d\TH:i');

        return view('driver.duty_status.edit', compact('log'));
    }

    // Actualizar log
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:ON,OFF,SB,D,WT,PC,YM',
            'changed_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
        ]);

        $log = dutystatuslog::findOrFail($id);

        $log->status = $request->status;
        $log->changed_at = Carbon::parse($request->changed_at);
        $log->location = $request->location;
        $log->notes = $request->notes;
        $log->save();

        return redirect()->route('driver.logs.today')
                        ->with('success', 'Log updated successfully.');
    }
}

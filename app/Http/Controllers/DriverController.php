<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    //Este lo usa el admin para las operaciones CRUD de drivers

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Mostrar todos los drivers
     */
    public function index()
    {
        $drivers = Driver::all();
        return view('admin.drivers.drivers', compact('drivers'));
    }

    /**
     * Mostrar formulario para crear un nuevo driver
     */
    public function create()
    {
        return view('admin.drivers.new_driver');
    }

    /**
     * Guardar un nuevo driver en la base de datos
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'social_security_number' => 'nullable|string|max:255|unique:drivers,social_security_number',
            'driver_license' => 'nullable|string|max:255|unique:drivers,driver_license',
            'email' => 'required|email|unique:drivers,email',
            'phone_number' => 'nullable|string|max:255',
        ]);

        Driver::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'social_security_number' => $request->social_security_number,
            'driver_license' => $request->driver_license,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver creado correctamente.');
    }

    /**
     * Mostrar formulario para editar un driver existente
     */
    public function edit($id)
    {
        $driver = Driver::findOrFail($id);
        return view('admin.drivers.edit', compact('driver'));
    }

    /**
     * Actualizar los datos de un driver
     */
    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'social_security_number' => 'nullable|string|max:255|unique:drivers,social_security_number,' . $driver->id,
            'driver_license' => 'nullable|string|max:255|unique:drivers,driver_license,' . $driver->id,
            'email' => 'required|email|unique:drivers,email,' . $driver->id,
            'phone_number' => 'nullable|string|max:255',
        ]);

        $driver->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'social_security_number' => $request->social_security_number,
            'driver_license' => $request->driver_license,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver actualizado correctamente.');
    }

    /**
     * Eliminar un driver
     */
    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();

        return redirect()->route('drivers.index')->with('success', 'Driver eliminado correctamente.');
    }
}

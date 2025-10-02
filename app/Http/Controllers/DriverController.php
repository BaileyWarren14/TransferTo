<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\drivers;
use Illuminate\Support\Facades\Hash;

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
        return view('admin.drivers.list_drivers', compact('drivers'));
    }

    public function create()
    {
        return view('admin.drivers.new_driver');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'nullable|email|unique:drivers',
            'password' => 'required|min:6'
        ]);

        Driver::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'social_security_number' => $request->social_security_number,
            'license_number' => $request->license_number,
            'password' => Hash::make($request->password),
            'status' => 'off',
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver added successfully.');
    }

    public function edit($id)
    {
        $driver = Driver::findOrFail($id);
        return view('admin.drivers.edit_driver', compact('driver'));
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'nullable|email|unique:drivers,email,'.$driver->id,
            'password' => 'nullable|min:6' // nuevo: password opcional
        ]);

        $driver->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'social_security_number' => $request->social_security_number,
            'license_number' => $request->license_number,
        ]);
        
        if ($request->filled('password')) {
            $driver->password = Hash::make($request->password);
            $driver->save();
        }

        return response()->json(['success' => true]);

    }

    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();

        return redirect()->route('drivers.index')->with('success', 'Driver deleted successfully.');
    }
    public function messages()
    {
        return view('driver.messages.index_messages');
    }

    public function safety()
    {
        return view('driver.safety.index_safety');
    }

    public function notifications()
    {
        return view('driver.notifications.index_notifications');
    }

    public function documents()
    {
        return view('driver.documents.index_documents');
    }
}

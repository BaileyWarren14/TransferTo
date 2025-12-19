<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\drivers;
use App\Models\Document;
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
    public function index(Request $request)
    {
        $drivers = Driver::query();

        if ($request->filled('name')) {
            $drivers->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('lastname')) {
            $drivers->where('lastname', 'like', '%' . $request->lastname . '%');
        }
        if ($request->filled('license_number')) {
            $drivers->where('license_number', 'like', '%' . $request->license_number . '%');
        }

        if ($request->filled('status')) {
            $drivers->where('status', $request->status);
        }

        $drivers = $drivers->get();

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
        $driver->notifications()->delete();
        $driver->delete();

        return redirect()->route('drivers.index')->with('success', 'Driver deleted successfully.');
    }
    public function messages()
    {
        return view('driver.messages.index_messages');
    }

    

    public function notifications()
    {
        return view('driver.notifications.index_notifications');
    }

    

    
    public function getVehicles()
    {
        // Puedes ajustar esto según tu modelo y relaciones
        $vehicles = \App\Models\Vehicle::select('id', 'license_plate','brand', 'model', 'year', 'color')->get();

        return response()->json($vehicles);
    }

    public function setVehicle(Request $request)
    {
        $vehicle = \App\Models\Vehicle::find($request->vehicle_id);

        if (!$vehicle) {
            return response()->json(['success' => false, 'message' => 'Vehicle not found']);
        }

        // Ejemplo: guardar en sesión o en la BD del conductor
        auth()->user()->update(['current_vehicle_id' => $vehicle->id]);

        return response()->json([
            'success' => true,
            'vehicle' => $vehicle
        ]);
    }
    public function documents($id)
    {
         $driver = Driver::findOrFail($id);

        // Documentos normales del driver
        $documents = $driver->documents;

        // BOLs de fuel del driver
        $fuelBOLs = $driver->fuels; // usando la relación fuels()

        return view('admin.drivers.documents', compact('driver', 'documents', 'fuelBOLs'));
        
    }
    public function show($id)
    {
        $doc = Document::findOrFail($id);

        // Asegúrate de que el archivo exista
        if (!\Storage::disk('public')->exists($doc->file_path)) {
            abort(404);
        }

        return response()->file(storage_path('app/public/' . $doc->file_path));
    }



}

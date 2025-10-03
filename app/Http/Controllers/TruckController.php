<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Inspection;
use App\Models\Truck;
use Carbon\Carbon;

class TruckController extends Controller
{
    //Esta funcion es para que en el new.blade.php se ponga en automatuco el odometro del truck seleccionado
    public function findByPlate($plate)
    {
        $truck = \App\Models\Truck::where('license_plate', $plate)->first();

        if ($truck) {
            return response()->json([
                'success' => true,
                'odometer' => $truck->current_mileage
            ]);
        }

        return response()->json(['success' => false]);
    }

    public function about()
    {
       $driver = Auth::guard('driver')->user();

        $today = Carbon::today();
        $inspection = Inspection::where('driver_id', $driver->id)
            ->whereDate('created_at', $today)
            ->first();

        if (!$inspection || !$inspection->truck_number) {
            return redirect()->route('driver.dashboard')
                ->with('alert_message', 'Please perform your inspection and select a truck.');
        }

        // Buscar el camión por placa
        $truck = Truck::where('license_plate', $inspection->truck_number)->first();

        if (!$truck) {
            return redirect()->route('driver.dashboard')
                ->with('alert_message', 'The selected truck was not found.');
        }

        return view('driver.about_truck.about_truck', compact('truck'));
    }

     // Listado de Trucks
    public function index()
    {
        $trucks = Truck::all();
        return view('admin.trucks.list_trucks', compact('trucks'));
    }

    // Formulario Crear Truck
    public function create()
    {
        $drivers = Driver::all();
        return view('admin.trucks.new_truck', compact('drivers'));
    }

    // Guardar Truck
    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => 'required|unique:trucks',
            'brand' => 'required',
            'model' => 'nullable',
            'year' => 'nullable|numeric',
            'current_mileage' => 'nullable|numeric',
            'fuel_capacity' => 'nullable|numeric',
            'color' => 'nullable|string',
            'cab_type' => 'nullable|string',
            'transmission_type' => 'nullable|string',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        $truck = Truck::create($validated);

        return response()->json(['success' => true, 'truck' => $truck]);
    }

    // Formulario Editar Truck
    public function edit(Truck $truck)
    {
        $drivers = Driver::all();
        return view('admin.trucks.edit_truck', compact('truck', 'drivers'));
    }

    // Actualizar Truck
    public function update(Request $request, Truck $truck)
    {
        $validated = $request->validate([
            'license_plate' => 'required|unique:trucks,license_plate,' . $truck->id,
            'brand' => 'required',
            'model' => 'nullable',
            'year' => 'nullable|numeric',
            'current_mileage' => 'nullable|numeric',
            'fuel_capacity' => 'nullable|numeric',
            'color' => 'nullable|string',
            'cab_type' => 'nullable|string',
            'transmission_type' => 'nullable|string',
            'driver_id' => 'nullable|exists:drivers,id',
        ]);

        $truck->update($validated);

        return response()->json(['success' => true, 'truck' => $truck]);
    }

    // Eliminar Truck
    public function destroy(Truck $truck)
    {
        $truck->delete();
        return response()->json(['success' => true]);
    }

    // Obtener horas de motor del truck asignado al driver
    public function motorHoursJson()
    {
        $driver = Auth::guard('driver')->user();
        $truck = Truck::where('driver_id', $driver->id)->first();

        if (!$truck) {
            return response()->json(['success' => false]);
        }

        return response()->json([
            'success' => true,
            'current_motor_hours' => $truck->current_motor_hours,
            'status' => $truck->status
        ]);
    }
    
    // Listado de todos los trucks con info relevante
    public function adminDashboard()
    {
        $trucks = Truck::with('driver')->get(); // traer el driver asignado también

        return view('admin.trucks.dashboard_trucks', compact('trucks'));
    }

    // Opcional: JSON para refresco AJAX de horas motor
    public function adminMotorHoursJson()
    {
        $trucks = Truck::with('driver')->get();

        $data = $trucks->map(function($truck){
            return [
                'id' => $truck->id,
                'license_plate' => $truck->license_plate,
                'driver_name' => $truck->driver ? $truck->driver->name : 'Unassigned',
                'current_motor_hours' => $truck->current_motor_hours,
                'status' => $truck->status
            ];
        });

        return response()->json($data);
    }

}

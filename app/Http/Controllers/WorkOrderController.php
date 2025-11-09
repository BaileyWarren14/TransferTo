<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fuel; 
use App\Models\Truck;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class WorkOrderController extends Controller
{
    //
    public function index()
    {
        return view('driver.details.menu');
    }

    // Mostrar lista
    public function indexes()
    {
        $fuels = Fuel::orderBy('date', 'desc')->paginate(10);
        return view('driver.details.list_fuel_cistern', compact('fuels'));
    }

    /**
     * Muestra el formulario para crear un nuevo registro
     */
   public function create()
    {
        $driver = Auth::guard('driver')->user();

        if (!$driver) {
            return redirect()->route('login')->with('error', 'You must be logged in as a driver.');
        }

        $truck = Truck::where('driver_id', $driver->id)->first();
        $currentMileage = $truck ? $truck->current_mileage : 0;

        return view('driver.details.details', compact('currentMileage', 'truck'));
    }

    /**
     * Guarda un nuevo registro de combustible
     */
    public function store(Request $request)
    {
        $driver = Auth::guard('driver')->user();
        $truck = $driver->truck;

        $request->validate([
            'date' => 'required|date',
            'bol_number' => 'required|string|max:255',
            'trailer' => 'required|string|max:255',
            'from' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'iso_capacity' => 'required|numeric|min:0',
            'inches_gallon' => 'required|numeric|min:0',
        ]);

        Fuel::create([
            'date' => $request->date,
            'bol_number' => $request->bol_number,
            'trailer' => $request->trailer,
            'from' => $request->from,
            'destination' => $request->destination,
            'iso_capacity' => $request->iso_capacity,
            'inches_gallon' => $request->inches_gallon,
            'mileage_before' => $truck ? $truck->current_mileage : 0,
            'mileage_after' => 0,
            'total_miles' => 0,
            'fuel_dispensed' => 0,
            'efficiency' => 0,
            'truck_id' => $truck ? $truck->id : null,
        ]);

        return redirect()->route('workorder.cistern.index')->with('success', 'Fuel log created successfully!');
    }

    public function edit(Fuel $fuel)
    {
        return view('driver.details.edit_fuel', compact('fuel'));
    }

    public function update(Request $request, Fuel $fuel)
    {
        $request->validate([
            'date' => 'required|date',
            'bol_number' => 'required|string|max:255',
            'trailer' => 'required|string|max:255',
            'from' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'iso_capacity' => 'required|numeric|min:0',
            'inches_gallon' => 'required|numeric|min:0',
            'mileage_before' => 'required|numeric|min:0',
        ]);

        $fuel->update($request->all());

        return redirect()->route('workorder.cistern.index')->with('success', 'Fuel log updated successfully!');
    }

    public function destroy(Fuel $fuel)
    {
        $fuel->delete();
        return redirect()->route('workorder.cistern.index')->with('success', 'Fuel log deleted successfully!');
    }

    public function uploadBol(Request $request, $id)
    {
        $fuel = Fuel::find($id);

        if (!$fuel) {
            return response()->json(['error' => 'Fuel record not found.'], 404);
        }

        $request->validate([
            'bol_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        // Si ya tiene un archivo anterior, eliminarlo
        if ($fuel->bol_path && Storage::disk('public')->exists($fuel->bol_path)) {
            Storage::disk('public')->delete($fuel->bol_path);
        }

        // Guardar el nuevo archivo
        $path = $request->file('bol_file')->store('bol_uploads', 'public');

        // Actualizar el registro
        $fuel->update(['bol_path' => $path]);

        return redirect()->back()->with('success', 'BOL uploaded successfully!');
    }

   public function finalize(Request $request, Fuel $fuel)
    {
        $request->validate([
            'mileage_after' => 'required|numeric|min:0',
            'fuel_dispensed' => 'required|numeric|min:0',
        ]);

        $totalMiles = $request->mileage_after - $fuel->mileage_before;
        $efficiency = $request->fuel_dispensed > 0 ? $totalMiles / $request->fuel_dispensed : 0;

        $fuel->update([
            'mileage_after' => $request->mileage_after,
            'total_miles' => $totalMiles,
            'fuel_dispensed' => $request->fuel_dispensed,
            'efficiency' => $efficiency,
        ]);

        // Actualizar mileage del camión
        if ($fuel->truck) {
            $fuel->truck->update([
                'current_mileage' => $request->mileage_after
            ]);
        }

        return redirect()->route('workorder.cistern.index')
                         ->with('success', 'Trip finalized successfully!');
    }



    public function cisterns()
    {
        return view('driver.details.details');
    }

    public function dryBox()
    {
        return view('driver.details.drybox');
    }

    public function platform()
    {
        return view('driver.details.platform');
    }

    public function pneumatic()
    {
        return view('driver.details.pneumatic');
    }
}

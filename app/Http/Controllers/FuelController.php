<?php

namespace App\Http\Controllers;

use App\Models\Fuel;
use Illuminate\Http\Request;

class FuelController extends Controller
{
    // Listar registros (vista principal)
    public function index()
    {
        $fuels = Fuel::latest()->paginate(10);
        return view('driver.details.list_fuel_cistern', compact('fuels'));
    }

    // Mostrar formulario
    public function create()
    {
        return view('driver.details.details');
    }

    // Guardar registro
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'bol_number' => 'required|string|max:255',
            'trailer' => 'required|string|max:255',
            'from' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'iso_capacity' => 'required|numeric',
            'inches_gallon' => 'required|numeric',
            'mileage_before' => 'required|numeric',
            'mileage_after' => 'required|numeric',
            'total_miles' => 'required|numeric',
            'fuel_dispensed' => 'required|numeric',
        ]);

        Fuel::create($validated);

        return redirect()->route('workorder.cistern.index')->with('success', 'Fuel log saved successfully.');
    }

    // Editar registro
    public function edit(Fuel $fuel)
    {
        return view('driver.fuel.edit', compact('fuel'));
    }

    // Actualizar registro
    public function update(Request $request, Fuel $fuel)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'bol_number' => 'required|string|max:255',
            'trailer' => 'required|string|max:255',
            'from' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'iso_capacity' => 'required|numeric',
            'inches_gallon' => 'required|numeric',
            'mileage_before' => 'required|numeric',
            'mileage_after' => 'required|numeric',
            'total_miles' => 'required|numeric',
            'fuel_dispensed' => 'required|numeric',
        ]);

        $fuel->update($validated);

        return redirect()->route('workorder.cistern.index')->with('success', 'Fuel log updated successfully.');
    }

    // Eliminar registro
    public function destroy(Fuel $fuel)
    {
        $fuel->delete();
        return redirect()->route('workorder.cistern.index')->with('success', 'Fuel log deleted successfully.');
    }
}

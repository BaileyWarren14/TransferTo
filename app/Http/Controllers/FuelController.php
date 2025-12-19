<?php

namespace App\Http\Controllers;

use App\Models\Fuel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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

     // Eliminar BOL de fuel
    public function FuelBOLdestroy($id)
    {
         $fuel = Fuel::findOrFail($id);

        if ($fuel->bol_path && Storage::disk('public')->exists($fuel->bol_path)) {
            Storage::disk('public')->delete($fuel->bol_path);
        }

        $fuel->bol_path = null;
        $fuel->save();


        return redirect()->back()->with('success', 'File deleted successfully');

    }

    // Descargar BOL de fuel
    public function FuelBOLdownload($id)
    {
          $fuel = Fuel::findOrFail($id);

            if (!$fuel->bol_path || !Storage::disk('public')->exists($fuel->bol_path)) {
                return back()->with('error', 'Archivo no encontrado.');
            }

            return Storage::disk('public')->download(
                $fuel->bol_path,
                'BOL-' . $fuel->bol_number . '.pdf'
            );
    }



     // Mostrar formulario para subir BOL
    public function createBOL($fuelId)
    {
        $fuel = Fuel::findOrFail($fuelId);
        return view('admin.fuelbol.create', compact('fuel'));
    }

    // Guardar BOL
    public function storeBOL(Request $request, $fuelId)
    {
        $fuel = Fuel::findOrFail($fuelId);

        $request->validate([
            'bol_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB
        ]);

        // Guardar archivo
        $filePath = $request->file('bol_file')->store('fuel_bols', 'public');

        // Actualizar el registro de Fuel con la ruta del archivo
        $fuel->bol_path = $filePath;
        $fuel->save();

        return redirect()->back()->with('success', 'Fuel BOL uploaded successfully.');
    }
}

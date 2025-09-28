<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trailer;

class TrailerController extends Controller
{
    //
     // Listado de Trailers
    public function index()
    {
        $trailers = Trailer::all();
        return view('admin.trailers.list_trailers', compact('trailers'));
    }

    // Formulario Crear Trailer
    public function create()
    {
        return view('admin.trailers.new_trailer');
    }

    // Guardar Trailer
    public function store(Request $request)
    {
        $validated = $request->validate([
            'axles' => 'required|numeric',
            'trailer_type' => 'required|string',
            'license_plate' => 'required|unique:trailers'
        ]);

        $trailer = Trailer::create($validated);

        return response()->json(['success' => true, 'trailer' => $trailer]);
    }

    // Formulario Editar Trailer
    public function edit(Trailer $trailer)
    {
        return view('admin.trailers.edit_trailer', compact('trailer'));
    }

    // Actualizar Trailer
    public function update(Request $request, Trailer $trailer)
    {
        $validated = $request->validate([
            'axles' => 'required|numeric',
            'trailer_type' => 'required|string',
            'license_plate' => 'required|unique:trailers,license_plate,' . $trailer->id
        ]);

        $trailer->update($validated);

        return response()->json(['success' => true, 'trailer' => $trailer]);
    }

    public function destroy(Trailer $trailer)
    {
        $trailer->delete();
         return redirect()->route('trailers.index')
                     ->with('success', 'Registro eliminado correctamente');

    }
}

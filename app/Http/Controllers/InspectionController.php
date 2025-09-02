<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InspectionController extends Controller
{
    //
    public function save(Request $request)
{
    // Guardar en la BD
    // Report::create($request->all());

    // Redirigir con mensaje de éxito
     return redirect()->back()->with('success', 'Inspection report saved successfully.');

}
}

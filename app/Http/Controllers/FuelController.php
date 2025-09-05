<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FuelController extends Controller
{
    //
    // Aquí guardas los datos en la BD
    // Ejemplo si tienes un modelo Fuel
    public function store(Request $request)
    {
        // Aquí guardas los datos en la BD
        // Ejemplo si tienes un modelo Fuel
        Fuel::create($request->all());

    return redirect()->back()->with('success', 'Fuel log saved successfully!');
    }
}

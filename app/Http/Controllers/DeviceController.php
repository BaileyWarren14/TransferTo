<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeviceController extends Controller
{
    //
    public function index()
    {
        return view('dashboard');
    }
    public function getLocation()
    {
        // Aquí puedes reemplazar estos valores con los de tu base de datos
        // Por ejemplo, obtener el último registro de ubicación de un dispositivo
        $lat = 20.6597; // Latitud de ejemplo
        $lng = -103.3496; // Longitud de ejemplo

        return response()->json([
            'lat' => $lat,
            'lng' => $lng,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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


}

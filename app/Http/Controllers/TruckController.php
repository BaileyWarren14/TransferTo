<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inspection;
use App\Models\Truck;
use App\Models\Trailer;

class InspectionController extends Controller
{
    //
    
    // Guardar en la BD
    // Report::create($request->all());

    // Redirigir con mensaje de éxito
    

    
     public function create()
    {
        $driver = Auth::user(); // usuario logueado
        $trucks = Truck::where('status', 'active')->get(); // camiones activos
        $trailers = Trailer::all(); // todos los trailers

        return view('inspection.create', compact('driver', 'trucks', 'trailers'));
    }

    public function store(Request $request)
    {
        $inspection = new Inspection();

        // Usuario logueado
        $inspection->driver_id = Auth::id();
        $inspection->truck_id = $request->truck_id;
        $inspection->trailer_id = $request->trailer_id;

        // Tipo de inspección
        $inspection->pre_trip = $request->has('pre_trip');
        $inspection->post_trip = $request->has('post_trip');

        // Información del camión
        $inspection->truck_number = $request->truck_number;
        $inspection->odometer = $request->odometer;
        $inspection->unit = $request->unit;

        // Condición
        $inspection->condition = $request->condition;

        // Checklist dinámico
        $checklist_fields = [
            'air_compressor','air_lines','axles','battery','belts','body_frame','brakes_adjustment','brakes_service',
            'brakes_parking','charging_system','clutch','cooling_system','coupling_devices','documents','doors',
            'drive_lines','emergency_equipment','emergency_windows','engine','exhaust_system','fire_extinguishers',
            'first_aid','fluid_leaks','frame','fuel_system','heater','horns','inspection_decals','interior_ligths',
            'lights_reflectors','load_security_device','lubrication_system','mirrows','mud_flaps','oil_pressure',
            'rear_end','recording_devices','seats','suspension','steering_mechanism','transmission','wheels_tires',
            'windows','wipers','other'
        ];

        foreach ($checklist_fields as $field) {
            $inspection->$field = $request->has($field);
        }

        // Trailer info
        $inspection->trailer1 = $request->trailer1;
        $inspection->trailer2 = $request->trailer2;

        // Remarks
        $inspection->remarks = $request->remarks;

        // Firma automática (si el usuario está logueado)
        if (Auth::check()) {
            $inspection->signature = Auth::user()->first_name . ' ' . Auth::user()->last_name;
        }

        // Fecha y hora automáticas
        $inspection->inspection_date = now()->toDateString();
        $inspection->inspection_time = now()->toTimeString();

        // Guardamos en DB
        $inspection->save();

        return redirect()->route('inspections.create')->with('success', 'Inspection saved successfully!');
    }
}


<?php

namespace App\Http\Controllers;
use PDF;
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
            $inspection->driver_id = Auth::id();
            $inspection->truck_number = $request->truck_number;
            $inspection->odometer = $request->odometer;
            $inspection->unit = $request->unit;
            $inspection->conditions = $request->conditions;
            $inspection->remarks = $request->remarks;
            $inspection->signature = $request->signature;
            $inspection->inspection_date = $request->inspection_date;
            $inspection->inspection_time = $request->inspection_time;
            $inspection->trailer1 = $request->trailer1;
            $inspection->trailer2 = $request->trailer2;

            $inspection->signature_agent = $request->signature_agent;
            $inspection->date_today2 = $request->date_today2;
            $inspection->hour_inspection2 = $request->hour_inspection2;

            // 🔹 Manejo de Pre-trip y Post-trip
            $inspection->pre_trip = $request->has('pre_trip');
            $inspection->post_trip = $request->has('post_trip');

            // 🔹 Manejo de above_not_corrected y above_corrected
            $inspection->above_not_corrected = $request->has('above_not_corrected');
            $inspection->above_corrected = $request->has('above_corrected');

            // 🔹 Guardamos checklist como JSON

            $inspection->checklist = json_encode($request->checklist);

            $inspection->save();

            return response()->json([
                'success' => true,
                'inspection_id' => $inspection->id
            ]);

        
        

        
        
       


    
    }
    public function generatePDF ($id)
    {
        
        $inspection = Inspection::findOrFail($id);
        // Aseguramos que checklist sea un array
         $checklist = $inspection->checklist;
        if (is_string($checklist)) {
            $decoded = json_decode($checklist, true);
            $checklist = is_array($decoded) ? $decoded : [];
        } elseif (!is_array($checklist)) {
            $checklist = [];
        }
        // Lista completa y ordenada de los 45 items (misma que en el form)
        $items = [
            'air_compressor','air_lines','axles','battery','belts','body_frame',
            'brakes_adjustment','brakes_service','brakes_parking','charging_system',
            'clutch','cooling_system','coupling_devices','documents','doors',

            'drive_lines','emergency_equipment','emergency_windows','engine','exhaust_system',
            'fire_extinguishers','first_aid','fluid_leaks','frame','fuel_system',
            'heater','horns','inspection_decals','interior_ligths','lights_reflectors',

            'load_security_device','lubrication_system','mirrows','mud_flaps','oil_pressure',
            'rear_end','recording_devices','seats','suspension','steering_mechanism',
            'transmission','wheels_tires','windows','wipers','other'
        ];
        $pdf = Pdf::loadView('pdf', [
            'inspection' => $inspection,
            'checklist' => $checklist, // pasamos ya como array
            'items'      => $items,
        ]);

        return $pdf->download('inspection.pdf');
    }
}


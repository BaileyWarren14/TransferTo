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

            // 🔹 Manejo de Pre-trip y Post-trip
            $inspection->pre_trip = $request->has('pre_trip');
            $inspection->post_trip = $request->has('post_trip');

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
         $checklist = [];
        if (!empty($inspection->checklist)) {
            if (is_array($inspection->checklist)) {
                $checklist = $inspection->checklist;
            } else {
                // Si es string separado por comas
                $checklist = explode(',', $inspection->checklist);
                // Opcional: limpiar espacios extra
                $checklist = array_map('trim', $checklist);
            }
        }

         $pdf = Pdf::loadView('pdf', compact('inspection', 'checklist'));
        return $pdf->download('inspection.pdf');

    }
}


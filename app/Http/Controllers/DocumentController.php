<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use App\Models\Fuel;
use App\Models\Driver;


class DocumentController extends Controller
{
    
     public function index()
    {
        $driverId = auth()->id();
        $documents = Document::all();
        
        return view('driver.documents.index_documents', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'file' => 'required|file|max:5120'
        ]);

         $driverId = auth()->id(); 

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents', $fileName, 'public');

        Document::create([
            'driver_id' => $driverId,
            'type' => $request->type,
            'file_name' => $fileName,
            'file_path' => $path
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

   public function download($id)
    {
        $doc = Document::findOrFail($id);

        // Verificar que el driver sea propietario
        if($doc->driver_id != auth()->id()){
            abort(403);
        }

        // Retornar el archivo con headers correctos
        return response()->download(storage_path('app/public/' . $doc->file_path), $doc->file_name);
    }

    public function show($id)
    {
        $doc = Document::findOrFail($id);

        // if ($doc->driver_id != auth('driver')->id()) {
        //     abort(403);
        // }

        return response()->file(storage_path('app/public/' . $doc->file_path));

    }

    public function show_ad($id)
    {
        $doc = Document::findOrFail($id);

        // if ($doc->driver_id != auth('driver')->id()) {
        //     abort(403);
        // }
        

        return response()->file(storage_path('app/public/' . $doc->file_path));

    }


    public function showFuelBOL($id)
    {
        $fuel = Fuel::findOrFail($id);

        // Verificar que el conductor sea propietario del camión
        if ($fuel->truck->driver_id != auth('driver')->id()) {
            abort(403);
        }

        return response()->file(storage_path('app/public/' . $fuel->bol_path));
    }

    public function destroy($id)
    {
        $doc = Document::findOrFail($id);
        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();

        return back()->with('success', 'Document deleted successfully.');
    }

    public function documents($driverId)
    {
        $driver = \App\Models\Driver::findOrFail($driverId);

        // Documentos normales del driver
        $documents = $driver->documents;

        // BOLs de Fuel Logs del conductor
        $fuelBOLs = \App\Models\Fuel::whereHas('truck', function($q) use ($driverId) {
            $q->where('driver_id', $driverId);
        })->whereNotNull('bol_path')->get();

        return view('admin.drivers.documents', compact('driver', 'documents', 'fuelBOLs'));
    }



}

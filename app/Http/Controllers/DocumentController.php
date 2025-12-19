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

        if ($doc->driver_id != auth('driver')->id()) {
            abort(403);
        }

        return response()->file(storage_path('app/public/' . $doc->file_path));
    }


    public function show_ad($id)
    {
        $document = Document::findOrFail($id);
        
        if (!$document->file_path) {
            abort(404);
        }

        $path = storage_path('app/public/' . $document->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);

    }
    public function viewFile($type, $id)
    {
        if ($type === 'document') {

            $doc = Document::findOrFail($id);

            $fullPath = storage_path('app/public/' . $doc->file_path);

            if (!file_exists($fullPath)) {
                abort(404, "Archivo no encontrado");
            }

            return response()->file($fullPath);
        }

        if ($type === 'bol') {

            $fuel = Fuel::findOrFail($id);

            $fullPath = storage_path('app/public/' . $fuel->bol_path);

            if (!file_exists($fullPath)) {
                abort(404, "Archivo BOL no encontrado");
            }

            return response()->file($fullPath);
        }

        abort(400, "Tipo no reconocido");
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
         $documents = Document::where('driver_id', $driverId)->get();

        // BOLs de Fuel Logs del conductor
        $fuelBOLs = \App\Models\Fuel::whereHas('truck', function($q) use ($driverId) {
            $q->where('driver_id', $driverId);
        })->whereNotNull('bol_path')->get();

        return view('admin.drivers.documents', compact('driver', 'documents', 'fuelBOLs'));
    }


     // Mostrar formulario de creación (admin)
    public function Adcreate($driverId)
    {
        return view('admin.documents.create', compact('driverId'));
    }

    // Eliminar documento (admin)
    public function AdminDestroyDocuments($id)
    {
        $doc = Document::findOrFail($id);

        // Eliminar archivo físico si existe
        if ($doc->file_path && Storage::exists('public/' . $doc->file_path)) {
            Storage::delete('public/' . $doc->file_path);
        }

        $doc->delete();

        return back()->with('success', 'Document deleted successfully.');
    }

    // Descargar documento (admin)
    public function AdminDownloadDocuments($id)
    {
        $doc = Document::findOrFail($id);

        $filePath = storage_path('app/public/' . $doc->file_path);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return response()->download($filePath, $doc->file_name);
    }

    //Ya no lo ocupo
    public function downloadBol($id)
    {
       $fuel = Fuel::findOrFail($id);

        $fullPath = storage_path('app/public/' . $fuel->bol_path);

        if (!file_exists($fullPath)) {
            abort(404, "Archivo BOL no encontrado");
        }

        return response()->download($fullPath);
    }

    //Ya no lo ocupo
    public function deleteBol($id)
    {
         $fuel = Fuel::findOrFail($id);

        $fullPath = storage_path('app/public/' . $fuel->bol_path);

        // Eliminar archivo físico
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        // Limpiar referencia en BD
        $fuel->bol_path = null;
        $fuel->save();

        return back()->with('success', 'BOL deleted successfully.');
        
    }


}

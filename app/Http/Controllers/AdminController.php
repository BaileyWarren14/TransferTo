<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
     // Listado de admins
    public function index()
    {
        $admins = Admin::all();
        return view('admin.admin.list_admin', compact('admins'));
    }
    // Formulario para crear nuevo admin
    public function create()
    {
        return view('admin.admin.new_admin');
    }

    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'required|email|unique:administrators,email',
            'password' => 'required|string|min:6',
            'department' => 'nullable|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        // Crear administrador
        Admin::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => bcrypt($request->password), // <--- Hashear la contraseña
            'department' => $request->department,
            'position' => $request->position,
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin added successfully.');
        
    }

     // Actualizar admin
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'required|email|unique:administrators,email,'.$admin->id,
            'department' => 'nullable|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        $admin->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'department' => $request->department,
            'position' => $request->position,
        ]);

        if($request->filled('password')){
            $admin->password = Hash::make($request->password);
            $admin->save();
        }

        return response()->json(['success' => true]);
    }

    // Eliminar admin
    public function destroy(Admin $admin)
    {
         // Evitar que el admin actual se elimine
        if ($admin->id === Auth::guard('admin')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete the currently logged-in admin.'
            ]);
        }

        $admin->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Admin deleted successfully.'
                ]);
            }

            return redirect()->route('admin.index')->with('success', 'Admin deleted successfully.');
    }

    // Formulario para editar admin
    public function edit(Admin $admin)
    {
        return view('admin.admin.edit_admin', compact('admin'));
    }

    // Dashboard principal
    public function dashboard()
    {
        return view('admin.dashboard'); // resources/views/admin/dashboard.blade.php
    }

    // Gestión de camiones
    public function trucks()
    {
        return view('admin.trucks'); // resources/views/admin/trucks.blade.php
    }

    // Gestión de remolques
    public function trailers()
    {
        return view('admin.trailers'); // resources/views/admin/trailers.blade.php
    }

    // Detalles generales
    public function details()
    {
        return view('admin.details'); // resources/views/admin/details.blade.php
    }
}

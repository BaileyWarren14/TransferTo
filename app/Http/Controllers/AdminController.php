<?php

namespace App\Http\Controllers;
use App\Models\Admin;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function create()
    {
        return view('admin.create');
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

        return redirect()->route('admin.create')->with('success', 'Administrador creado correctamente.');
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

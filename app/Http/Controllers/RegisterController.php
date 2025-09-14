<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    public function showRegisterForm()
    {
        return view('register'); // tu vista register.blade.php
    }

    public function register(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone_number' => 'required|numeric|digits_between:10,10',
            'social_security_number' => 'required|string|min:10|max:10|unique:drivers,social_security_number',
            'license_number' => 'required|string|max:50|unique:drivers,license_number',
            'email' => 'required|email|unique:drivers,email',
            'password' => 'required|confirmed|min:6',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'phone_number' => $request->phone_number,
            'social_security_number' => Hash::make($request->social_security_number),
            'license_number' => $request->license_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);        

        if (!$user) {
            return back()->with('error', 'No se pudo registrar el usuario.');
        }

        // Guardar mensaje en sesión
        

        // Redirigir a la ruta donde mostramos el SweetAlert
         return redirect()->route('log')->with('success', 'User created successfully. Please log in.');
    }
    public function registerSuccess($user_id)
    {
        $user = User::findOrFail($user_id);

        // Mostrar la vista con SweetAlert
        return view('register_success', compact('user'));
    }
}

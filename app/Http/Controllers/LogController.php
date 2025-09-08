<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('log'); // tu vista log.blade.php
    }

    public function login(Request $request)
    {
         // Validación de los datos antes de intentar login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

       if (Auth::guard('web')->attempt($credentials)) {
            // Login exitoso como driver
            return redirect()->intended('/dashboard'); // Ruta del dashboard de drivers
        }

        // Intentar login como admin
        if (Auth::guard('admin')->attempt($credentials)) {
            // Login exitoso como administrador
            return redirect()->intended('/admin/dashboard'); // Ruta del dashboard de admins
        }

        // Autenticación fallida
        return back()->with('error', 'Invalid credentials');
    }

    public function logout()
    {
        Auth::guard('driver')->logout();

        return redirect()->route('login'); // redirige al login
    }
    
}


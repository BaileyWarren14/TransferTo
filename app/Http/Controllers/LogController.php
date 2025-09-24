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
        // Validar los datos del formulario
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // 🔹 Intentar login como Admin
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        // 🔹 Intentar login como Driver
        if (Auth::guard('driver')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/driver/dashboard');
        }

        // Si llega aquí → credenciales inválidas
        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
       // Verifica qué guard está activo
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('driver')->check()) {
            Auth::guard('driver')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('log');
            
        }
    
}


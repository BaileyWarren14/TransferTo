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

       
        // Login de driver
        if (Auth::guard('driver')->attempt($credentials)) {
            $request->session()->regenerate(); // importante para seguridad
            return redirect()->intended('/driver/dashboard'); 
        }

         // Login de admin
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

       

        // Autenticación fallida
        return back()->with('error', 'Invalid credentials');
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


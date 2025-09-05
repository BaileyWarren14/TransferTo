<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('log'); // tu vista login.blade.php
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Autenticación correcta
            return redirect()->intended('dashboard'); // redirige al dashboard
        }

        // Autenticación fallida
        return back()->with('error', 'Invalid credentials');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('log');
    }
    
}


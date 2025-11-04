<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    //
    public function showLoginForm()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return view('log'); // tu vista log.blade.php
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Intenta login en ambos guards
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard')
                            ->with('status', ['type' => 'success', 'message' => 'Login successful']);
        }

        if (Auth::guard('driver')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/driver/dashboard')
                            ->with('status', ['type' => 'success', 'message' => 'Login successful']);
        }

        // Credenciales inválidas
        return redirect()->route('log')
                        ->with('status', ['type' => 'error', 'message' => 'Invalid username or password']);
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
        public function logoutd(Request $request)
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
    //mandar a llamar el logut en el login
}


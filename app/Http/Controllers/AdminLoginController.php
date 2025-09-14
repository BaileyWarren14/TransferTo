<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm() {
        return view('admin.login'); // tu vista
    }

    public function login(Request $request) {
         // Validación de los datos antes de intentar login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        // Login de admin
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

       

        // Autenticación fallida
        return back()->with('error', 'Invalid credentials');
    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.log');
    }
}

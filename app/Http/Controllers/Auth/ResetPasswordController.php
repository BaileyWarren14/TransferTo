<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Driver;
use App\Models\Admin;




class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
   

    // Mostrar formulario de reset
    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->query('email');

        // Detectar tipo de usuario según URL
        $userType = str_contains($request->path(), 'admin') ? 'admin' : 'driver';

        return view('auth.reset_password', [
            'token' => $token,
            'email' => $email,
            'userType' => $userType
        ]);
    }

    // Procesar reset de contraseña
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Detectar broker según email
        $broker = null;

        if (Driver::where('email', $request->email)->exists()) {
            $broker = 'drivers';
        } elseif (Admin::where('email', $request->email)->exists()) {
            $broker = 'admins';
        }

        if (!$broker) {
            return back()->withErrors(['email' => 'Este correo no está registrado.']);
        }

        $status = Password::broker($broker)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect($request->userType === 'admin' ? '/log' : '/log')
                ->with('password_reset_success', true)
            : back()->withErrors(['email' => 'The token is invalid or has expired.']);
    }
}

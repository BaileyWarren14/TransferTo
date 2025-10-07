<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\Driver;
use App\Models\Admin;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

     public function showLinkRequestForm()
    {
        return view('auth.forgot_password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Detectar si el correo pertenece a un Driver o Admin
        $userType = null;

        if (Driver::where('email', $request->email)->exists()) {
            $userType = 'drivers';
        } elseif (Admin::where('email', $request->email)->exists()) {
            $userType = 'admins';
        }

        if (!$userType) {
            return back()->withErrors(['email' => 'Email not found in our records.']);
        }

        // Seleccionar el broker dinámico
        $status = Password::broker($userType)->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}

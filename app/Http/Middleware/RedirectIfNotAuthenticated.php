<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticated
{
     public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('log') // 👈 ajusta al nombre de tu login
                ->with('error', 'You must log in to continue');
        }

        return $next($request);
    }
}

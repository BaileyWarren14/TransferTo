<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard($guard)->check()) {
            // Si es admin
            if ($guard === 'admin') {
                return redirect()->route('admin.login')
                    ->with('error', 'You must log in to continue');
            }

            // Si es driver u otro usuario
            return redirect()->route('log')
                ->with('error', 'You must log in to continue');
        }

        return $next($request);
    }
}

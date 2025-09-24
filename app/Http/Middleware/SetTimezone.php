<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle($request, Closure $next)
    {
        if(Session::has('timezone')){
            $tz = Session::get('timezone');
            Config::set('app.timezone', $tz);
            date_default_timezone_set($tz);
        }
        return $next($request);
    }
}

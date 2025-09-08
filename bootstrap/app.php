<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 👇 Aquí registras tu middleware con alias
        $middleware->alias([
            'auth.custom' => \App\Http\Middleware\RedirectIfNotAuthenticated::class,
        ]);
    })
    ->withMiddleware(function (Illuminate\Foundation\Configuration\Middleware $middleware) {
    $middleware->alias([
        'ensure.auth' => \App\Http\Middleware\EnsureUserIsAuthenticated::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

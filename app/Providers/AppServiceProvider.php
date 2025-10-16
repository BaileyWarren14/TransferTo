<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\dutystatuslog;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer(['layouts.sidebar'], function ($view) {
        if (Auth::guard('driver')->check()) {
            $driver = Auth::guard('driver')->user();
            $lastLog = dutystatuslog::where('driver_id', $driver->id)
                ->orderBy('changed_at', 'desc')
                ->first();

            $view->with('sidebar_status', $lastLog ? $lastLog->status : 'OFF');
            $view->with('sidebar_log', $lastLog);
        }
    });
    }
}

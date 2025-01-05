<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\MaintenanceModeManager;
use Illuminate\Contracts\Foundation\MaintenanceMode;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MaintenanceMode::class, function ($app) {
            return $app->make(MaintenanceModeManager::class)->driver();
        });
    }

    public function boot(): void
    {
        // Auth モジュールのビューを登録
        View::addNamespace('Auth', base_path('app/Modules/Auth/Views'));
        
        // Home モジュールのビューを登録
        View::addNamespace('Home', base_path('app/Modules/Home/Views'));
    }
}

<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/profile';

    public function boot(): void
    {
        $this->routes(function () {
            // メインのルート
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Homeモジュールのルート
            if (file_exists(base_path('app/Modules/Home/Routes/web.php'))) {
                Route::middleware('web')
                    ->group(base_path('app/Modules/Home/Routes/web.php'));
            }

            // Appモジュールのルート
            if (file_exists(base_path('app/Modules/App/Routes/web.php'))) {
                Route::middleware('web')
                    ->group(base_path('app/Modules/App/Routes/web.php'));
            }

            // Profileモジュールのルート
            if (file_exists(base_path('app/Modules/Profile/Routes/web.php'))) {
                Route::middleware('web')
                    ->group(base_path('app/Modules/Profile/Routes/web.php'));
            }
        });
    }
} 
<?php

namespace App\Modules\AppV2;

use Illuminate\Support\ServiceProvider;
use App\Modules\AppV2\Services\CloudinaryService;

class AppV2ServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Config
        $this->mergeConfigFrom(
            __DIR__.'/Config/constants.php', 'appv2.constants'
        );

        // CloudinaryServiceの登録
        $this->app->singleton(CloudinaryService::class, function ($app) {
            return new CloudinaryService();
        });
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');
        $this->loadViewsFrom(__DIR__ . '/Views', 'AppV2');
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
    }
} 
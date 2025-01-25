<?php

namespace App\Modules\AppV2;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Modules\AppV2\Services\CloudinaryService;
use Filament\Facades\Filament;
use App\Modules\AppV2\Filament\Resources\AppV2Resource;

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

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');
        $this->loadViewsFrom(__DIR__ . '/Views', 'AppV2');
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

        // コンポーネントの登録を追加
        Blade::componentNamespace('App\\Modules\\AppV2\\Views\\Components', 'appv2');

        // Filamentリソースの登録
        Filament::registerResources([
            AppV2Resource::class,
        ]);
    }
} 
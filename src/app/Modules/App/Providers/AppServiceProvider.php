<?php

namespace App\Modules\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'app'
        );
        // ヘルパー関数を登録
        require_once app_path('Modules/App/Helpers/ColorHelper.php');
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../Views', 'app');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        // コンポーネントの登録
        Blade::componentNamespace('App\\Modules\\App\\Views\\components', 'app');

        // マイグレーションパスの登録
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
} 
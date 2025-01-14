<?php

namespace App\Modules\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Modules\App\Services\AppProgressManager;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'app'
        );
        // ヘルパー関数を登録
        require_once app_path('Modules/App/Helpers/ColorHelper.php');

        // AppProgressManagerの登録を追加
        $this->app->singleton(AppProgressManager::class, function ($app) {
            return new AppProgressManager();
        });
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'App');
        // コンポーネントの登録を修正
        Blade::componentNamespace('App\\Modules\\App\\Views\\components', 'app');

        // マイグレーションパスの登録
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
} 
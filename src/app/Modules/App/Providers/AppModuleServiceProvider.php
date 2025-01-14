<?php

namespace App\Modules\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Modules\App\Services\AppProgressManager;

class AppModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'app'
        );

        // AppProgressManagerの登録
        $this->app->singleton(AppProgressManager::class, function ($app) {
            return new AppProgressManager();
        });
    }

    public function boot(): void
    {
        // ルートの読み込み
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // ビューの読み込み - この行が重要！
        $this->loadViewsFrom(__DIR__ . '/../Views', 'App');  // 'App'が名前空間

        // コンポーネントの登録
        Blade::componentNamespace('App\\Modules\\App\\Views\\components', 'app');

        // マイグレーションの読み込み
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
} 
<?php

namespace App\Modules\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Modules\App\Services\AppProgressManager;
use Filament\Facades\Filament;
use App\Modules\App\Filament\Resources\AppResource;

class AppModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'app-module'
        );

        // 定数ファイルの読み込み方を修正
        $this->mergeConfigFrom(
            __DIR__.'/../Config/constants.php', 'app-module.constants'
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

        // ビューの読み込み（Viewsをviewsに変更）
        $this->loadViewsFrom(__DIR__ . '/../views', 'App');

        // コンポーネントの登録
        Blade::componentNamespace('App\\Modules\\App\\Views\\components', 'app');

        // マイグレーションの読み込み
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // Filamentリソースの登録を戻す
        Filament::registerResources([
            AppResource::class,
        ]);
    }
} 
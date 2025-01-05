<?php

namespace App\Modules\AppPost;

use App\Modules\AppPost\Models\AppPost;
use App\Modules\AppPost\Policies\AppPostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppPostServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // ここに追加
        $this->app->bind('files', function () {
            return new \Illuminate\Filesystem\Filesystem();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // ビューの場所を登録
        $this->loadViewsFrom(__DIR__ . '/Views', 'AppPost');

        // マイグレーションの場所を登録
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

        // ルートの登録
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        // ポリシーの登録
        Gate::policy(AppPost::class, AppPostPolicy::class);
    }
} 
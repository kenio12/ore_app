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
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // ビューの登録
        $this->loadViewsFrom(__DIR__ . '/Views', 'AppPost');

        // ルートの登録
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        // ポリシーの登録
        Gate::policy(AppPost::class, AppPostPolicy::class);
    }
} 
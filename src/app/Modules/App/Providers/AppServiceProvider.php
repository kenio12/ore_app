<?php

namespace App\Modules\App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'app'
        );
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../Views', 'App');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }
} 
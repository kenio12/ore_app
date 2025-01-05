<?php

namespace App\Modules\Home\Providers;

use Illuminate\Support\ServiceProvider;

class HomeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'home'
        );
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../Views', 'Home');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }
} 
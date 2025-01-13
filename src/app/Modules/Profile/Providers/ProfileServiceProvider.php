<?php

namespace App\Modules\Profile\Providers;

use Illuminate\Support\ServiceProvider;

class ProfileServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'profile'
        );
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../Views', 'Profile');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
} 
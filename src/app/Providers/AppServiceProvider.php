<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Appモジュールの設定を登録
        $this->mergeConfigFrom(
            base_path('app/Modules/App/Config/cloudinary.php'), 
            'App::cloudinary'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 本番環境ではHTTPSを強制
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // AppPostのルート読み込みを削除
        // $this->loadRoutesFrom(base_path('app/Modules/AppPost/Routes/web.php'));
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Modules\AppV2\Models\App;  // 正しいパスを指定

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

        // すべてのSQLクエリをログに記録
        DB::listen(function($query) {
            // IDに関連するクエリのみをログ
            if (str_contains($query->sql, 'apps')) {
                Log::debug('SQL実行:', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time,
                    'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
                ]);
            }
        });

        // Appモデルの作成をログ
        App::created(function ($app) {
            Log::debug('新規App作成:', [
                'id' => $app->id,
                'url' => request()->url(),
                'method' => request()->method(),
                'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
            ]);
        });

        // AppPostのルート読み込みを削除
        // $this->loadRoutesFrom(base_path('app/Modules/AppPost/Routes/web.php'));
    }
}

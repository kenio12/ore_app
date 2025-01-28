<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Home\Controllers\HomeController;
use App\Modules\App\Controllers\AppController;
use App\Http\Controllers\Auth\PasswordController;
use App\Modules\AppV2\Controllers\AppController as AppV2Controller;
use App\Http\Controllers\DashboardController;

// メインページのルート
Route::get('/', [HomeController::class, 'index']);

// 認証ルートを有効化
require __DIR__.'/auth.php';

// ダッシュボードルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // パスワード変更ルート（既存）
    Route::get('/password', [PasswordController::class, 'edit'])
        ->name('password.edit');
});

// メール認証が必要なルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/apps/create', [AppController::class, 'create'])->name('apps.create');
});

// AppV2のルート（追加）
require __DIR__.'/../app/Modules/AppV2/Routes/web.php';


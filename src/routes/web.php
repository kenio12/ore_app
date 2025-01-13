<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Home\Controllers\HomeController;
use App\Modules\App\Controllers\AppController;
use App\Http\Controllers\Auth\PasswordController;

// メインページのルート
Route::get('/', [HomeController::class, 'index']);

// 認証ルートを有効化
require __DIR__.'/auth.php';

// ダッシュボードルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $apps = auth()->user()->apps;
        return view('dashboard', compact('apps'));
    })->name('dashboard');

    // パスワード変更ルート（追加）
    Route::get('/password', [PasswordController::class, 'edit'])
        ->name('password.edit');
});

// メール認証が必要なルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/apps/create', [AppController::class, 'create'])->name('apps.create');
});


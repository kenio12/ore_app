<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Home\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Modules\App\Controllers\AppController;

Route::get('/', [HomeController::class, 'index']);

// 認証ルートを有効化
require __DIR__.'/auth.php';

// ダッシュボードルートを追加
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// メール認証が必要なルートをここに記述
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/apps/create', [AppController::class, 'create'])->name('apps.create');
    // 他のメール認証が必要なルートもここに
});


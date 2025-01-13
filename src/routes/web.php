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
        return view('dashboard');
    })->name('dashboard');
});

// メール認証が必要なルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/apps/create', [AppController::class, 'create'])->name('apps.create');
});

Route::middleware('auth')->group(function () {
    Route::get('/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});


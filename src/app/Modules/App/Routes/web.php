<?php

use App\Modules\App\Controllers\AppController;
use App\Modules\App\Controllers\Sections\A_BasicInfoController;
use App\Modules\App\Controllers\Sections\B_DevelopmentStoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])->group(function () {
    // アプリの基本ルート
    Route::get('/apps', [AppController::class, 'index'])->name('apps.index');
    Route::get('/apps/create', [AppController::class, 'create'])->name('apps.create');
    Route::get('/apps/{app}', [AppController::class, 'show'])->name('apps.show');

    // セクション別のルート
    Route::prefix('apps/sections')->name('app.sections.')->group(function () {
        // 基本情報セクション
        Route::get('/basic-info/{app?}', [A_BasicInfoController::class, 'edit'])
            ->name('basic-info.edit');
        Route::post('/basic-info', [A_BasicInfoController::class, 'store'])
            ->name('basic-info.store');
        Route::put('/basic-info/{app}', [A_BasicInfoController::class, 'update'])
            ->name('basic-info.update');

        // 開発ストーリーセクション
        Route::get('/development-story/{app}', [B_DevelopmentStoryController::class, 'edit'])
            ->name('development-story.edit');
        Route::put('/development-story/{app}', [B_DevelopmentStoryController::class, 'update'])
            ->name('development-story.update');
    });

    // 編集画面のルートを追加
    Route::get('/apps/{app}/edit', [AppController::class, 'edit'])->name('apps.edit');
});

Route::middleware('auth')->group(function () {
    // ダッシュボード用のルート（編集モード）
    Route::get('/dashboard/apps/{app}/edit', [AppController::class, 'edit'])
        ->name('dashboard.apps.edit');
    
    // 公開ページ用のルート（閲覧モード）
    Route::get('/apps/{app}', [AppController::class, 'show'])
        ->name('apps.show');
}); 
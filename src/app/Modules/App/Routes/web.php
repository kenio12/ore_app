<?php

use App\Modules\App\Controllers\AppController;
use App\Modules\App\Controllers\Sections\A_BasicInfoController;
use App\Modules\App\Controllers\Sections\B_DevelopmentStoryController;
use Illuminate\Support\Facades\Route;

// 認証が必要なルートをまとめて定義
Route::middleware(['web', 'auth', 'verified'])->group(function () {
    // createとstoreのルート
    Route::get('/apps/create', [AppController::class, 'create'])->name('apps.create');
    Route::post('/basic-info', [A_BasicInfoController::class, 'store'])
        ->name('basic-info.store');
    
    // セクション別のルート
    Route::prefix('apps/sections')->name('app.sections.')->group(function () {
        // 基本情報セクション
        Route::get('/basic-info/{app?}', [A_BasicInfoController::class, 'edit'])
            ->name('basic-info.edit');
        Route::post('/basic-info', [A_BasicInfoController::class, 'store'])
            ->name('basic-info.store');
        Route::put('/basic-info/{app}', [A_BasicInfoController::class, 'update'])
            ->name('basic-info.update');
        Route::get('/basic-info/{app}/next', [A_BasicInfoController::class, 'next'])
            ->name('basic-info.next');

        // 開発ストーリーセクション
        Route::get('/development-story/{app}', [B_DevelopmentStoryController::class, 'edit'])
            ->name('development-story.edit');
        Route::put('/development-story/{app}', [B_DevelopmentStoryController::class, 'update'])
            ->name('development-story.update');
        Route::get('/development-story/{app}/next', [B_DevelopmentStoryController::class, 'next'])
            ->name('development-story.next');
    });

    // 編集画面のルート
    Route::get('/apps/{app}/edit', [AppController::class, 'edit'])->name('apps.edit');
});

// 認証不要の公開ルートを後に
Route::middleware(['web'])->group(function () {
    Route::get('/apps', [AppController::class, 'index'])->name('apps.index');
    Route::get('/apps/{app}', [AppController::class, 'show'])->name('apps.show');
});

// ダッシュボード用のルート
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/apps/{app}/edit', [AppController::class, 'edit'])
        ->name('dashboard.apps.edit');
}); 
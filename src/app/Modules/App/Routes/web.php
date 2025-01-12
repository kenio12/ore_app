<?php

use Illuminate\Support\Facades\Route;
use App\Modules\App\Controllers\AppController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('apps')->group(function () {
        Route::get('/', [AppController::class, 'index'])->name('apps.index');
        Route::get('/create', [AppController::class, 'create'])->name('apps.create');
        Route::post('/', [AppController::class, 'store'])->name('apps.store');
        Route::get('{app}/basic-info', [AppController::class, 'editBasicInfo'])
            ->name('apps.basic-info.edit');
        Route::put('{app}/basic-info', [AppController::class, 'updateBasicInfo'])
            ->name('apps.basic-info.update');
        
        Route::get('{app}/development-story', [AppController::class, 'editDevelopmentStory'])
            ->name('apps.development-story.edit');
        Route::put('{app}/development-story', [AppController::class, 'updateDevelopmentStory'])
            ->name('apps.development-story.update');
    });
}); 
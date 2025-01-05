<?php

use Illuminate\Support\Facades\Route;
use App\Modules\App\Controllers\AppController;

Route::middleware(['web'])->group(function () {
    // 表示用ルート
    Route::get('/apps', [AppController::class, 'index'])->name('apps.index');
    Route::get('/apps/create', [AppController::class, 'create'])->name('apps.create');
    Route::get('/apps/{app}', [AppController::class, 'show'])->name('apps.show');
    Route::get('/apps/{app}/edit', [AppController::class, 'edit'])->name('apps.edit');
    
    // 保存用ルート
    Route::post('/apps', [AppController::class, 'store'])->name('apps.store');
    Route::put('/apps/{app}', [AppController::class, 'update'])->name('apps.update');
}); 
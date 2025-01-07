<?php

use Illuminate\Support\Facades\Route;
use App\Modules\App\Controllers\AppController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('apps')->group(function () {
        Route::get('/', [AppController::class, 'index'])->name('apps.index');
        Route::get('/create', [AppController::class, 'create'])->name('apps.create');
        Route::post('/', [AppController::class, 'store'])->name('apps.store');
    });
}); 
<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Profile\Controllers\ProfileController;
use App\Modules\Profile\Controllers\PasswordChangeController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // パスワード変更用のルート
    Route::get('/profile/password', [PasswordChangeController::class, 'show'])
        ->name('profile.password.change');
    Route::post('/profile/password', [PasswordChangeController::class, 'update'])
        ->name('profile.password.update');
}); 
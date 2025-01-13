<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Profile\Controllers\ProfileController;

Route::middleware(['web', 'auth'])->group(function () {
    // プロフィール表示
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');

    // プロフィール編集
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    // プロフィール更新
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // プロフィール削除
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
}); 
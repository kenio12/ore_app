<?php

use App\Modules\AppPost\Controllers\AppPostController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::prefix('app-posts')->name('app-posts.')->group(function () {
        // 一覧表示（認証不要）
        Route::get('/', [AppPostController::class, 'index'])->name('index')->withoutMiddleware(['auth']);
        
        // 詳細表示（認証不要だが、非公開は作成者のみ）
        Route::get('/{post}', [AppPostController::class, 'show'])->name('show')->withoutMiddleware(['auth']);
        
        // 新規作成
        Route::get('/create', [AppPostController::class, 'create'])->name('create');
        Route::post('/', [AppPostController::class, 'store'])->name('store');
        
        // 編集・更新
        Route::get('/{post}/edit', [AppPostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [AppPostController::class, 'update'])->name('update');
        
        // 削除
        Route::delete('/{post}', [AppPostController::class, 'destroy'])->name('destroy');
    });
}); 
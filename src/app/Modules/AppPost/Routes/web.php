<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AppPost\Controllers\AppPostController;
use App\Modules\AppPost\Models\AppPost;

// 認証付きルート
Route::middleware(['auth'])->group(function () {
    Route::resource('app-posts', AppPostController::class);
}); 
<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AppPost\Controllers\AppPostController;

// 認証不要のルート
Route::get('/', function () {
    return view('home');
});

// 既存の認証付きルート
Route::middleware(['auth'])->group(function () {
    Route::resource('app-posts', AppPostController::class);
}); 
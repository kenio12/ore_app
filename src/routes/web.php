<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Home\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);

// 認証ルートを有効化
require __DIR__.'/auth.php';

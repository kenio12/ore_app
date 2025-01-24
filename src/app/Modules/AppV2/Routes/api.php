<?php

use Illuminate\Support\Facades\Route;
use App\Modules\AppV2\Controllers\ScreenshotController;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::prefix('api/v2')->group(function () {
        Route::post('/screenshots/upload', [ScreenshotController::class, 'upload'])
            ->name('api.v2.screenshots.upload');
        Route::post('/screenshots/delete', [ScreenshotController::class, 'delete'])
            ->name('api.v2.screenshots.delete');
    });
}); 
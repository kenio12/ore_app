<?php

use App\Modules\AppV2\Controllers\AppController as AppV2Controller;
use App\Modules\AppV2\Controllers\ScreenshotController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::prefix('apps-v2')->group(function () {
        Route::get('/', [AppV2Controller::class, 'index'])->name('apps-v2.index');
        
        Route::get('/create', [AppV2Controller::class, 'create'])
            ->name('apps-v2.create');
        
        Route::post('/', [AppV2Controller::class, 'store'])
            ->name('apps-v2.store');
        
        Route::get('/edit/{app}', [AppV2Controller::class, 'edit'])
            ->where('app', '[0-9]+')
            ->name('apps-v2.edit');

        Route::post('/{app}/autosave', [AppV2Controller::class, 'autosave'])
            ->where('app', '[0-9]+')
            ->name('apps-v2.autosave');

        Route::get('/{app}', [AppV2Controller::class, 'show'])
            ->where('app', '[0-9]+')
            ->name('apps-v2.show');

        Route::prefix('screenshots')->group(function () {
            Route::post('/upload', [ScreenshotController::class, 'upload'])
                ->name('apps-v2.screenshots.upload');
            Route::post('/delete', [ScreenshotController::class, 'delete'])
                ->name('apps-v2.screenshots.delete');
        });
    });
}); 
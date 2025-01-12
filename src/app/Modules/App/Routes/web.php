<?php

use App\Modules\App\Controllers\AppController;

Route::middleware(['auth'])->group(function () {
    Route::get('/apps/create/{section?}', [AppController::class, 'create'])
         ->name('app.create');
    
    Route::post('/apps/{section}', [AppController::class, 'store'])
         ->name('app.store');
         
    Route::get('/apps/{uuid}', [AppController::class, 'show'])
         ->name('app.show');
}); 
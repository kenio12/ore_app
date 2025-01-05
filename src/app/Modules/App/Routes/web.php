<?php

use App\Modules\App\Controllers\AppController;

Route::middleware(['auth'])->group(function () {
    Route::resource('apps', AppController::class);
}); 
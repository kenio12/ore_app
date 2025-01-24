<?php

use App\Modules\AppV2\Controllers\AppController as AppV2Controller;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/apps-v2', [AppV2Controller::class, 'index'])->name('apps-v2.index');
    Route::get('/apps-v2/create', [AppV2Controller::class, 'create'])->name('apps-v2.create');
    Route::post('/apps-v2', [AppV2Controller::class, 'store'])->name('apps-v2.store');
    Route::get('/apps-v2/{app}', [AppV2Controller::class, 'show'])->name('apps-v2.show');
    Route::get('/apps-v2/{app}/edit', [AppV2Controller::class, 'edit'])->name('apps-v2.edit');
    Route::put('/apps-v2/{app}', [AppV2Controller::class, 'update'])->name('apps-v2.update');
    Route::delete('/apps-v2/{app}', [AppV2Controller::class, 'destroy'])->name('apps-v2.destroy');
    Route::post('/apps-v2/{app}/autosave', [AppV2Controller::class, 'autosave'])->name('apps-v2.autosave');
}); 
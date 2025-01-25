<?php

use App\Modules\AppV2\Controllers\AppController as AppV2Controller;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('apps-v2')->group(function () {
    // 基本的なCRUDルート
    Route::get('/', [AppV2Controller::class, 'index'])->name('apps-v2.index');
    Route::get('/create', [AppV2Controller::class, 'create'])->name('apps-v2.create');
    Route::post('/', [AppV2Controller::class, 'store'])->name('apps-v2.store');
    Route::get('/{app}', [AppV2Controller::class, 'show'])->name('apps-v2.show');
    Route::get('/{app}/edit', [AppV2Controller::class, 'edit'])->name('apps-v2.edit');
    Route::put('/{app}', [AppV2Controller::class, 'update'])->name('apps-v2.update');
    Route::delete('/{app}', [AppV2Controller::class, 'destroy'])->name('apps-v2.destroy');

    // 自動保存用のルート
    Route::post('/apps-v2/create/autosave', [AppV2Controller::class, 'autosave'])
        ->name('apps-v2.autosave.create');
    Route::post('/apps-v2/{app}/autosave', [AppV2Controller::class, 'autosave'])
        ->name('apps-v2.autosave.update');
}); 
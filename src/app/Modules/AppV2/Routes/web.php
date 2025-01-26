<?php

use App\Modules\AppV2\Controllers\AppController as AppV2Controller;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::prefix('apps-v2')->group(function () {
        Route::get('/', [AppV2Controller::class, 'index'])->name('apps-v2.index');
        Route::get('/edit/{app}', [AppV2Controller::class, 'edit'])->name('apps-v2.edit');
        Route::get('/{app}', [AppV2Controller::class, 'show'])->name('apps-v2.show');
        
        // autosaveルートを追加
        Route::post('/{app}/autosave', [AppV2Controller::class, 'autosave'])
            ->name('apps-v2.autosave');
    });
}); 
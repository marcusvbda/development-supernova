<?php

use marcusvbda\supernova\ModulesController;

Route::group(['middleware' => ['web']], function () {
    Route::get('login', [ModulesController::class, 'login'])->name('supernova.login');
    Route::group(['middleware' => ['supernova-default-middleware']], function () {
        Route::get('logout', [ModulesController::class, 'logout'])->name('supernova.logout');
        Route::get('', [ModulesController::class, 'dashboard'])->name('supernova.home');
        Route::get('{module}', [ModulesController::class, 'index'])->name('supernova.modules.index');
    });
});

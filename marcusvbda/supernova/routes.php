<?php

use marcusvbda\supernova\ModulesController;

Route::get('login', [ModulesController::class, 'login'])->name('supernova.login');


Route::group(['middleware' => ['web', 'supernova-default-middleware']], function () {
    Route::get('', [ModulesController::class, 'dashboard'])->name('supernova.home');
    Route::get('{module}', [ModulesController::class, 'index'])->name('supernova.modules.index');
});

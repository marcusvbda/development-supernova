<?php

use marcusvbda\supernova\ModulesController;

Route::group(['middleware' => ['web', 'supernova-default-middleware']], function () {
    Route::get('{module}', [ModulesController::class, 'index'])->name('supernova.modules.index');
});

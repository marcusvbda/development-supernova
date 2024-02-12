<?php

use App\Http\Supernova\Application;

return [
    "modules_path" => 'App\\Http\\Supernova\\Modules\\',
    "application" => Application::class,
    "modules_template" => "supernova::layouts.default",
];

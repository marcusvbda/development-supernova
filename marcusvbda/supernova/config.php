<?php

use App\Http\Supernova\Application;

return [
    "modules_namespace" => 'App\\Http\\Supernova\\Modules\\',
    "modules_path" => "Http/Supernova/Modules/",
    "application" => Application::class,
    "modules_template" => "supernova::layouts.default",
];

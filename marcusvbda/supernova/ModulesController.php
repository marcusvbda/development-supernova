<?php

namespace marcusvbda\supernova;

use App\Http\Controllers\Controller;
use App\Http\Supernova\Application;

class ModulesController extends Controller
{
    public function index($module)
    {
        $app = app()->make(config("supernova.application", Application::class));
        $module = $app->getModule($module);
        if (!$module->canViewIndex()) abort(403);
        return $module->index();
    }
}

<?php

namespace marcusvbda\supernova;

use App\Http\Controllers\Controller;

class ModulesController extends Controller
{
    protected $modulesPath = "";

    public function __construct()
    {
        $this->modulesPath = config("supernova.modules_path", "App\\Http\\Supernova\\Modules\\");
    }

    private function getModule($module)
    {
        $module = $this->modulesPath . ucfirst($module);
        try {
            return app()->make($module);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    public function index($module)
    {
        $module = $this->getModule($module);
        if (!$module->canViewIndex()) abort(403);
        return $module->index();
    }
}

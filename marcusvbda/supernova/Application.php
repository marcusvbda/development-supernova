<?php

namespace marcusvbda\supernova;

class Application
{
    protected $modulesNamespace = "";
    protected $modulesPath = "";

    public function __construct()
    {
        $this->modulesNamespace = config("supernova.modules_namespace", "App\\Http\\Supernova\\Modules\\");
        $this->modulesPath = config("supernova.modules_path", "Http/Supernova/Modules/");
    }

    public function middleware($request, $next)
    {
        return $next($request);
    }

    public function title(): string
    {
        return config("app.name");
    }

    public function styles(): string
    {
        return <<<CSS
            /* styles here ... */
        CSS;
    }

    public function icon(): string
    {
        return "favicon.ico";
    }

    public function getModule($module): Module
    {
        $module = $this->modulesNamespace . ucfirst($module);
        if (!class_exists($module)) abort(404);
        return app()->make($module);
    }

    public function getAllModules(): array
    {
        $path = $this->modulesPath;
        $namespace = $this->modulesNamespace;
        $modules = [];
        foreach (scandir(app_path($path)) as $item) {
            if ($item != "." && $item != "..") {
                $modules[] = app()->make($namespace . ucfirst(str_replace(".php", "", $item)));
            }
        }

        return $modules;
    }
}

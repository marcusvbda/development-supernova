<?php

namespace marcusvbda\supernova;

use Auth;

class Application
{
    protected $modulesNamespace = "";
    protected $modulesPath = "";

    public function __construct()
    {
        $this->modulesNamespace = config("supernova.modules_namespace", "App\\Http\\Supernova\\Modules\\");
        $this->modulesPath = config("supernova.modules_path", "Http/Supernova/Modules/");
    }

    public function homeTitle()
    {
        return "Dashboard";
    }

    public function middleware($request, $next)
    {
        return $next($request); // remover
        if (Auth::check()) return $next($request);
        return redirect()->route('supernova.login', ["redirect" => request()->path()]);
    }

    public function menuUserNavbar(): array
    {
        return [
            "element" => <<<BLADE
                <img class="h-8 w-8 rounded-full" src="https://images.squarespace-cdn.com/content/v1/61252ad026b2035cd08c26a6/1658329270544-UI18QS4NLSP83HOA0WUB/user-placeholder-avatar.png?format=2500w">
            BLADE,
            "items" => [
                "Sair" => route("supernova.login")
            ]
        ];
    }

    public function logo(): string
    {
        return <<<BLADE
            <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500">
        BLADE;
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

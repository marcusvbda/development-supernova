<?php

namespace marcusvbda\supernova;

use App\Models\User;
use Auth;
use marcusvbda\supernova\livewire\components\Datatable;
use marcusvbda\supernova\livewire\components\Login;
use marcusvbda\supernova\livewire\components\Navbar;

class Application
{
    protected $modulesNamespace;
    protected $modulesPath;

    public function __construct()
    {
        $this->modulesNamespace = config("supernova.modules_namespace", "App\\Http\\Supernova\\Modules\\");
        $this->modulesPath = config("supernova.modules_path", "Http/Supernova/Modules/");
    }

    public function homeTitle(): string
    {
        return "Dashboard";
    }

    public function middleware($request, $next)
    {
        if (Auth::check()) return $next($request);
        return redirect()->route('supernova.login', ["redirect" => request()->path()]);
    }

    public function menuUserNavbar(): array
    {
        $items = [];
        $items["Sair"] = route("supernova.logout");

        $user = Auth::user();
        return [
            "element" => <<<BLADE
                <div class="flex items-center gap-3">
                    <span class='text-gray-200 font-medium'>$user->name</span>
                    <img class="h-8 w-8 rounded-full" src="$user->avatarImage">
                </div>
            BLADE,
            "items" => $items
        ];
    }

    public function logo(): string
    {
        return "https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500";
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

    public function loginForm(): string
    {
        return Login::class;
    }

    public function navbar(): string
    {
        return Navbar::class;
    }

    public function datatable(): string
    {
        return Datatable::class;
    }

    public function UserModel(): string
    {
        return User::class;
    }
}

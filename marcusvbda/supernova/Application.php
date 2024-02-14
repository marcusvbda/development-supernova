<?php

namespace marcusvbda\supernova;

use App\Models\User;
use Auth;
use marcusvbda\supernova\livewire\components\Breadcrumb;
use marcusvbda\supernova\livewire\components\CounterCard;
use marcusvbda\supernova\livewire\components\Dashboard;
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

    public function darkMode(): bool
    {
        return true;
    }

    public function menuUserNavbar(): array
    {
        $items = [];
        $items["Sair"] = route("supernova.logout");

        $user = Auth::user();
        return [
            "element" => <<<BLADE
                <div class="flex items-center gap-3">
                    <img class="h-8 w-8 rounded-full" src="$user->avatarImage">
                    <span class='dark:text-gray-200 font-medium'>$user->name</span>
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

    public function Breadcrumb(): string
    {
        return Breadcrumb::class;
    }

    public function DashboardGreetingMessage()
    {
        $user = Auth::user();
        $hour = date('H');
        $sufix = ($user?->firstName ?? $user->name) . "!";
        if ($hour >= 5 && $hour <= 12) {
            return "Bom dia, $sufix";
        } else if ($hour > 12 && $hour <= 18) {
            return "Boa tarde, $sufix";
        } else {
            return "Boa noite, $sufix";
        }
    }

    public function dashboard(): string
    {
        return Dashboard::class;
    }

    public function counterCard(): string
    {
        return CounterCard::class;
    }

    public function dashboardContent()
    {
        $modules = $this->getAllModules();
        $counters = [];
        foreach ($modules as $module) {
            $counter = $module->dashboardCounterCard();
            if ($counter) {
                $counters[] = $counter;
            }
        }

        return compact("counters");
    }

    public function cardCounterReloadTime(): int
    {
        return 60;
    }

    public function menuItems(): array
    {
        $modules =  $this->getAllModules();
        $items = [];
        foreach ($modules as $module) {
            if (!$module->menu()) continue;
            $menu = $module->menu();
            if (!strpos($menu, ".")) {
                [$title, $url] =  $this->extractItemDetails($menu);
                $items[$title] = $url;
            } else {
                $menu = explode(".", $menu);
                [$title, $url] =  $this->extractItemDetails($menu[1]);
                $items[$menu[0]][$title] = $url;
            }
        }
        return $items;
    }

    public function extractItemDetails($item)
    {
        $url = str_replace("'", "", str_replace("href='", "", str_replace("}", "", explode("{", $item)[1])));
        $title =  substr($item, 0, strpos($item, "{"));
        return [$title, $url];
    }
}

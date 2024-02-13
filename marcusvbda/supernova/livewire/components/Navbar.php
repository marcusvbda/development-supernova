<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Livewire\Component;
use Auth;

class Navbar extends Component
{
    public $items;
    public $currentUrl;
    public $logo;
    public $homeTitle;
    public $homeRoute;
    public $menuUserNavbar;

    public function __construct()
    {
        $this->application = app()->make(config("supernova.application", Application::class));
    }

    private function makeSettings(): void
    {
        $modules =  $this->application->getAllModules();
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
        $this->items = $items;
        $this->currentUrl = request()->url();
        $this->logo = $this->application->logo();
        $this->homeTitle = $this->application->homeTitle();
        $this->homeRoute = route("supernova.home");
        $this->menuUserNavbar = $this->application->menuUserNavbar();
    }

    public function extractItemDetails($item)
    {
        $url = str_replace("'", "", str_replace("href='", "", str_replace("}", "", explode("{", $item)[1])));
        $title =  substr($item, 0, strpos($item, "{"));
        return [$title, $url];
    }

    public function render()
    {
        if (!Auth::check() && $this->application->secureRoutes()) return <<<BLADE
            <div></div>
        BLADE;
        $this->makeSettings();
        return view('supernova-livewire-views::navbar');
    }
}

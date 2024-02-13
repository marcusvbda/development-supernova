<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Livewire\Component;

class Navbar extends Component
{
    public $items;
    public $currentUrl;
    public $logo;
    public $homeTitle;
    public $homeRoute;
    public $menuUserNavbar;

    private function makeSettings(): void
    {
        $application = app()->make(config("supernova.application", Application::class));
        $modules =  $application->getAllModules();
        $items = [];
        foreach ($modules as $module) {
            if (!$module->menu()) continue;
            $menu = $module->menu();
            if (!strpos($menu, ".")) {
                $items[$menu] = route("supernova.modules.index", ["module" => strtolower($menu)]);
            } else {
                $menu = explode(".", $menu);
                $items[$menu[0]][$menu[1]] = route("supernova.modules.index", ["module" => strtolower($menu[1])]);
            }
        }
        $this->items = $items;
        $this->currentUrl = request()->url();
        $this->logo = $application->logo();
        $this->homeTitle = $application->homeTitle();
        $this->homeRoute = route("supernova.home");
        $this->menuUserNavbar = $application->menuUserNavbar();
    }

    public function render()
    {
        $this->makeSettings();
        return view('supernova-livewire-views::navbar');
    }
}

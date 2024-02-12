<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Livewire\Component;

class Navbar extends Component
{
    public $items = [];
    private function makeItems(): void
    {
        $app = app()->make(config("supernova.application", Application::class));
        $modules = $app->getAllModules();
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
    }

    public function render()
    {
        $this->makeItems();
        return view('supernova-livewire-views::navbar');
    }
}

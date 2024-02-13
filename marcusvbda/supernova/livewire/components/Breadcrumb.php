<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Livewire\Component;

class Breadcrumb extends Component
{
    public $items = [];
    public function mount()
    {
        $application = app()->make(config("supernova.application", Application::class));
        $route = request()->route();
        $currentRoute = $route->getName();
        $currentRouteParams = $route->parameters();
        $moduleId = data_get($currentRouteParams, "module");
        $this->items[] = [
            "title" => $application->homeTitle(),
            "route" => route("supernova.home"),
        ];
        if ($currentRoute != "supernova.home" && $moduleId) {
            if (strpos($currentRoute, "supernova.modules") === 0) {
                $module = $application->getModule($moduleId);
                $this->items[] = [
                    "title" => $module->name()[1],
                    "route" => route("supernova.modules.index", ["module" => $moduleId]),
                ];
            }
        }
    }

    public function render()
    {
        return view('supernova-livewire-views::breadcrumb');
    }
}

<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Livewire\Component;

class Breadcrumb extends Component
{
    public $items = [];
    public $entityUrl = null;
    public $entityId = null;
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
            if ($currentRoute === "supernova.modules.index") {
                $module = $application->getModule($moduleId);
                $this->items[] = [
                    "title" => $module->name()[1],
                    "route" => route("supernova.modules.index", ["module" => $moduleId]),
                ];
            }
            if ($currentRoute === "supernova.modules.details") {
                $module = $application->getModule($moduleId);
                $this->items[] = [
                    "title" => $module->name()[1],
                    "route" => route("supernova.modules.index", ["module" => $moduleId]),
                ];
                $this->items[] = [
                    "title" => $module->name()[0] . " #" . $this->entityId,
                    "route" => $this->entityUrl
                ];
            }
            if ($currentRoute === "supernova.modules.create") {
                $module = $application->getModule($moduleId);
                $this->items[] = [
                    "title" => $module->name()[1],
                    "route" => route("supernova.modules.index", ["module" => $moduleId]),
                ];
                $this->items[] = [
                    "title" => $module->title("create"),
                    "route" => route("supernova.modules.create", ["module" => $moduleId]),
                ];
            }
            if ($currentRoute === "supernova.modules.edit") {
                $module = $application->getModule($moduleId);
                $this->items[] = [
                    "title" => $module->name()[1],
                    "route" => route("supernova.modules.index", ["module" => $moduleId]),
                ];
                $this->items[] = [
                    "title" => $module->name()[0] . " #" . $this->entityId,
                    "route" => route("supernova.modules.details", ["module" => $moduleId, 'id' => $this->entityId]),
                ];
                $this->items[] = [
                    "title" => $module->title("edit"),
                    "route" => route("supernova.modules.edit", ["module" => $moduleId, 'id' => $this->entityId]),
                ];
            }
        }
    }

    public function render()
    {
        return view('supernova-livewire-views::breadcrumb');
    }
}

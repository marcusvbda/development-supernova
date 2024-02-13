<?php

namespace marcusvbda\supernova;

use  Illuminate\View\View;

class Module
{
    public function model(): string
    {
        return "your model here ...";
    }

    public function getCacheQtyKey(): string
    {
        return 'qty:' . $this->id();
    }

    public function clearCacheQty(): void
    {
        cache()->forget($this->getCacheQtyKey());
    }

    public function getCachedQty()
    {
        $cacheTime = 60 * 24;
        return cache()->remember($this->getCacheQtyKey(), $cacheTime, function () {
            return app()->make($this->model())->count();
        });
    }

    public function title($page): string
    {
        $name = $this->name();
        return match ($page) {
            'index' =>  "Listagem de " . data_get($name, 1, $this->id()),
            default => $this->id()
        };
    }

    public function id(): string
    {
        $name = class_basename(get_class($this));
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $name));
    }

    public function canViewIndex(): bool
    {
        return true;
    }

    public function index(): View
    {
        $module = $this;
        return view("supernova::modules.index", compact("module"));
    }

    public function name(): array
    {
        $id = $this->id();
        $singular = ucfirst((substr($id, -1) === 's') ? substr($id, 0, -1) : $id);
        $plural = ucfirst((substr($id, -1) === 's') ? $id : $id . 's');
        return [$singular, $plural];
    }

    public function subMenu(): ?string
    {
        return null;
    }

    public function menu(): string
    {
        $sub = $this->subMenu();
        $menu = $this->name()[1];
        $url = route("supernova.modules.index", ["module" => strtolower($this->id())]);
        return $sub ? "$sub.$menu{href='$url'}" : "$menu{href='$url'}";
    }

    public function dashboardCounterCard(): ?string
    {
        $moduleId = $this->id();
        return <<<BLADE
            @livewire('supernova::counter-card',[
                'module' => '$moduleId',
            ])
        BLADE;
    }
}

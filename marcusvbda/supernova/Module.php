<?php

namespace marcusvbda\supernova;

use  Illuminate\View\View;

class Module
{
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
        return class_basename(get_class($this));
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
        $singular = (substr($id, -1) === 's') ? substr($id, 0, -1) : $id;
        $plural = (substr($id, -1) === 's') ? $id : $id . 's';
        return [$singular, $plural];
    }

    public function subMenu(): string
    {
        return "";
    }

    public function menu()
    {
        $sub = $this->subMenu();
        $menu = $this->name()[1];
        return $sub ? "$sub.$menu" : $menu;
    }
}

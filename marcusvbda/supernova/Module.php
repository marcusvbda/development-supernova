<?php

namespace marcusvbda\supernova;

class Module
{
    public function id()
    {
        return class_basename(get_class($this));
    }

    public function canViewIndex(): bool
    {
        return true;
    }

    public function index()
    {
        $moduleId = $this->id();
        return view("supernova::modules.index", compact("moduleId"));
    }
}

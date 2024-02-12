<?php

namespace marcusvbda\supernova;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

// use Livewire\Mechanisms\ComponentRegistry;
// use marcusvbda\supernova\components\DataTable;

class Module
{
    public function id()
    {
        $moduleName = explode("\\", get_class($this));
        return Crypt::encryptString(strtolower($moduleName[count($moduleName) - 1]));
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

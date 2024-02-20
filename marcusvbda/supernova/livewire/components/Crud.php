<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Livewire\Component;

class Crud extends Component
{
    public $module;
    public $entity;
    public $panels = [];

    public function placeholder()
    {
        return view('supernova-livewire-views::skeleton', ['size' => '500px']);
    }

    private function getModule()
    {
        $application = app()->make(config('supernova.application', Application::class));
        return $application->getModule($this->module);
    }

    public function render()
    {
        return view('supernova-livewire-views::crud.index');
    }
}

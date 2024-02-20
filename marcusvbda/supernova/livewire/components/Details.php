<?php

namespace marcusvbda\supernova\livewire\components;

use Livewire\Component;

class Details extends Component
{
    public $module;
    public $entity;
    public $panels = [];

    public function placeholder()
    {
        return view('supernova-livewire-views::skeleton', ['size' => '500px']);
    }

    public function render()
    {
        return view('supernova-livewire-views::details.index');
    }
}

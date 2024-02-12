<?php

namespace marcusvbda\supernova\components;

use Livewire\Component;

class Datatable extends Component
{
    public $count = 1;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }

    public function render()
    {
        return view('supernova::components.datatable-view');
    }
}

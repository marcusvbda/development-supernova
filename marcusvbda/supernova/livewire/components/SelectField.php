<?php

namespace marcusvbda\supernova\livewire\components;

use Livewire\Component;

class SelectField extends Component
{
    public $options = [];
    public $model;
    public $onchange;
    public $field;
    public $multiple = false;
    public $loaded = false;
    public function placeholder()
    {
        return view('supernova-livewire-views::skeleton', ['size' => '38px', 'class' => 'rounded-md']);
    }

    public function change($val, $label)
    {
        if ($this->onchange) {
            $this->dispatch($this->onchange, $this->field, $val, $label, $this->multiple ? 'multiple-select' : 'select');
        }
    }

    public function loadData()
    {
        $this->loaded = true;
        $this->options = [
            ["value" => 'Clientes', "label" => "Clientes"],
            ["value" => 'Squads', "label" => "Squads"]
        ];
    }

    public function render()
    {
        return view('supernova-livewire-views::select-field');
    }
}

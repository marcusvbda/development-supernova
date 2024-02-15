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
        sleep(1);
        $this->loaded = true;
        $this->options = [
            ["value" => 1, "label" => "Option 1"],
            ["value" => 2, "label" => "Option 2"],
            ["value" => 3, "label" => "Option 3"],
            ["value" => 4, "label" => "Option 4"],
            ["value" => 5, "label" => "Option 5"],
        ];
    }

    public function render()
    {
        return view('supernova-livewire-views::select-field');
    }
}

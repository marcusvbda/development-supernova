<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Livewire\Component;

class SelectField extends Component
{
    public $options = [];
    public $model;
    public $onchange;
    public $field;
    public $module = null;
    public $multiple = false;
    public $loaded = false;
    public $type = 'field';

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

    private function getAppModule()
    {
        $application = app()->make(config("supernova.application", Application::class));
        return $application->getModule($this->module);
    }

    public function loadData()
    {
        if ($this->type === 'datatable_filter') {
            $module = $this->getAppModule();
            $columns = $module->dataTable();
            $field = collect($columns)->first(fn ($row) => $row->name === $this->field);
            $this->options = $field->filter_options;
            $action = $field->filter_options_callback;
            if (is_callable($action)) {
                $this->options = $action($module->makeModel());
            }
        }
        $this->loaded = true;
    }

    public function render()
    {
        return view('supernova-livewire-views::select-field');
    }
}

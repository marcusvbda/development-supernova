<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Livewire\Component;

class Crud extends Component
{
    public $module;
    public $entity;
    public $panels = [];
    public $values = [];
    public $options = [];
    public $loaded_options = [];

    public function placeholder()
    {
        return view('supernova-livewire-views::skeleton', ['size' => '500px']);
    }

    public function rules()
    {
        $module = $this->getModule();
        $fields = $module->fields();
        $rules = [];
        foreach ($fields as $field) {
            if ($field->rules) {
                $rules["values." . $field->field] = $field->rules;
            }
        }
        return $rules;
    }

    public function messages()
    {
        $module = $this->getModule();
        $fields = $module->fields();
        $messages = [];
        foreach ($fields as $field) {
            if ($field->messages && count($field->messages)) {
                $index = "values." . $field->field;
                foreach ($field->messages as $key => $value) {
                    $messages[$index . "." . $key] = $value;
                }
            }
        }
        return $messages;
    }

    public function validationAttributes()
    {
        $module = $this->getModule();
        $fields = $module->fields();
        $attr = [];
        foreach ($fields as $field) {
            if ($field->rules) {
                $attr["values." . $field->field] = $field->field;
            }
        }
        return $attr;
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    private function getModule()
    {
        $application = app()->make(config('supernova.application', Application::class));
        return $application->getModule($this->module);
    }

    public function loadInputOptions($field)
    {
        $module = $this->getModule();
        $fields = $module->fields();
        $field = collect($fields)->first(function ($f) use ($field) {
            return $f->field == $field;
        });
        $options_callback = $field->options_callback;
        if ($options_callback && is_callable($options_callback)) {
            $this->options[$field->field] = $options_callback();
        } else {
            $this->options[$field->field] = $field->options;
        }
        $this->loaded_options[$field->field] = true;
    }

    public function render()
    {
        return view('supernova-livewire-views::crud.index');
    }
}

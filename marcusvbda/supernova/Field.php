<?php

namespace marcusvbda\supernova;

class Field
{
    public $field;
    public $label;
    public $noData;
    public $model;
    public $limit = 1;
    public $detailCallback;
    public $type = "text";
    public $rules = [];
    public $messages = [];
    public $option_keys = ['value' => 'id', 'label' => 'name'];
    public $options_callback;
    public $options = [];
    public $visible = true;
    public $visibleOnDetails = true;

    public static function make($field, $label = null): Field
    {
        return new static($field, $label);
    }

    public function __construct($field, $label = null)
    {
        $this->field = $field;
        $this->label = $label ? $label : $field;
        $this->noData = config("supernova.placeholder_no_data", "<span>   -   </span>");
        $this->detailCallback = fn ($entity) => @$entity?->{$this->field} ?? $this->noData;
    }

    public function type($type): Field
    {
        $this->type = is_string($type) ? $type : @$type->value;
        if ($this->type === FIELD_TYPES::TEXT->value) {
            $this->detailCallback = fn ($entity) => @$entity?->{$this->field} ?? $this->noData;
        }
        if ($this->type === FIELD_TYPES::SELECT->value) {
            $this->detailCallback = function ($entity) {
                if (!$this->model) {
                    $value = @$entity?->{$this->field} ?? null;
                    $option = collect($this->options)->first(fn ($row) => $row["value"] == $value);
                    return $option ? $option["label"] : $this->noData;
                } else {
                    $value = @$entity?->{$this->field} ?? null;
                    if (!$value) return $this->noData;
                    $valueContent = @$value?->{data_get($this->option_keys, 'label')} ?? null;
                    return $valueContent ? $valueContent : $this->noData;
                }
            };

            $this->options_callback = function () {
                return $this->model->orderBy(data_get($this->option_keys, 'label'), "asc")->get()->map(function ($row) {
                    return ["value" => $row->{data_get($this->option_keys, 'value')}, "label" => $row->{data_get($this->option_keys, 'label')}];
                })->toArray();
            };
        }
        return $this;
    }

    public function rules($val, $messages = []): Field
    {
        $this->rules = $val;
        $this->messages = $messages;
        return $this;
    }

    public function canSee($val): Field
    {
        $this->visible = $val;
        return $this;
    }

    public function detailCallback($callback): Field
    {
        $this->detailCallback = $callback;
        return $this;
    }

    public function canSeeOnDetails($val): Field
    {
        $this->visibleOnDetails = $val;
        return $this;
    }

    public function optionKeys($keys): Field
    {
        $this->option_keys = $keys;
        return $this;
    }

    public function options($options): Field
    {
        if (is_array($options)) {
            $this->options = array_map(function ($row) {
                if (is_array($row) && array_key_exists("value", $row) && array_key_exists("label", $row)) {
                    return $row;
                }
                return ["value" => $row, "label" => $row];
            }, $options);
        } else {
            $this->model = app()->make($options);
            $this->options = $this->model->orderBy(data_get($this->option_keys, 'label'), "asc")->get()->map(function ($row) {
                return ["value" => $row->{data_get($this->option_keys, 'value')}, "label" => $row->{data_get($this->option_keys, 'label')}];
            })->toArray();
        }
        return $this;
    }

    public function limit($limit): Field
    {
        $this->limit = $limit;
        return $this;
    }
}

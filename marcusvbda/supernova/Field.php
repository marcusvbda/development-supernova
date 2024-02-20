<?php

namespace marcusvbda\supernova;

class Field
{
    public $field;
    public $label;
    public $noData;
    public $detailAction;
    public $type = "text";
    public $rules = [];
    public $visible = true;

    public static function make($field, $label = null): Field
    {
        return new static($field, $label);
    }

    public function __construct($field, $label = null)
    {
        $this->field = $field;
        $this->label = $label ? $label : $field;
        $this->noData = config("supernova.placeholder_no_data", "<span>   -   </span>");
        $this->detailAction = fn ($entity) => @$entity?->{$this->field} ?? $this->noData;
    }

    public function type($val): Field
    {
        $this->type = $val;
        if ($val === FIELD_TYPES::TEXT) {
            $this->detailAction = fn ($entity) => @$entity?->{$this->field} ?? $this->noData;
        }
        return $this;
    }

    public function rules($val): Field
    {
        $this->rules = $val;
        return $this;
    }

    public function canSee($val): Field
    {
        $this->visible = $val;
        return $this;
    }

    public function detailAction($callback): Field
    {
        $this->detailAction = $callback;
        return $this;
    }
}

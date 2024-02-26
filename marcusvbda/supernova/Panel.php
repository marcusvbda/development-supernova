<?php

namespace marcusvbda\supernova;

class Panel
{
    public $fields = [];
    public $label;
    public $visible = true;

    public static function make($label): Panel
    {
        return new static($label);
    }

    public function __construct($label)
    {
        $this->label = $label;
    }

    public function fields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    public function canSee($val): Panel
    {
        $this->visible = $val;
        return $this;
    }
}

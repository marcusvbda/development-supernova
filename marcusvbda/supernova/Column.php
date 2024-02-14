<?php

namespace marcusvbda\supernova;

class Column
{
    public $name;
    public $label;
    public $searchable = false;
    public $align = "right";
    public $filterable = false;
    public $sortable = false;
    public $width;
    public $minWidth;
    public $action;
    public function __construct($name)
    {
        $this->name = $name;
        $this->action = fn ($row) => $row->{$name};
    }

    public static function name($val): Column
    {
        $self = new static($val);
        return $self;
    }

    public function label($val): Column
    {
        $this->label = $val;
        return $this;
    }

    public function searchable($value = true): Column
    {
        $this->searchable = $value;
        return $this;
    }

    public function alignRight(): Column
    {
        $this->align = "right";
        return $this;
    }

    public function alignCenter(): Column
    {
        $this->align = "center";
        return $this;
    }

    public function alignLeft(): Column
    {
        $this->align = "left";
        return $this;
    }

    public function filterable($value = true): Column
    {
        $this->filterable = $value;
        if ($value && !$this->minWidth) {
            $this->minWidth("100px");
        }
        return $this;
    }

    public function sortable($value = true): Column
    {
        $this->sortable = $value;
        return $this;
    }

    public function width($value): Column
    {
        $this->width = $value;
        return $this;
    }

    public function minWidth($value): Column
    {
        $this->minWidth = $value;
        return $this;
    }

    public function action($value): Column
    {
        $this->action = $value;
        return $this;
    }
}

<?php

namespace marcusvbda\supernova;

class Column
{
    public $name;
    public $label;
    public $searchable = false;
    public $align = "justify-start";
    public $filterable = false;
    public $filter_type;
    public $sortable = false;
    public $width;
    public $minWidth;
    public $action;

    public function __construct($name)
    {
        $this->name = $name;
        $this->label = $name;
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
        $this->align = "justify-end";
        return $this;
    }

    public function alignCenter(): Column
    {
        $this->align = "justify-center";
        return $this;
    }

    public function alignLeft(): Column
    {
        $this->align = "justify-start";
        return $this;
    }

    public function filterable(FILTER_TYPES $type): Column
    {
        $this->filterable = true;
        $this->filter_type = $type->value;
        if (!$this->minWidth) {
            $this->minWidth("200px");
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

    public function callback($value): Column
    {
        $this->action = $value;
        return $this;
    }
}

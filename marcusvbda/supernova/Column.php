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
    public $filter_options = [];
    public $filter_options_callback;
    public $sortable = false;
    public $width;
    public $minWidth;
    public $action;
    public $visible = true;
    public $filterOptionsLimit;

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

    public function filterable(FILTER_TYPES $type, $filterOptionsLimit = null): Column
    {
        $this->filterable = true;
        $this->filter_type = $type->value;
        if (!$this->minWidth) {
            $this->minWidth("200px");
        }
        if ($type === FILTER_TYPES::SELECT) {
            $this->filterOptionsLimit = $filterOptionsLimit;
        }
        return $this;
    }

    public function filterOptions($options): Column
    {
        $this->filter_options = $options;
        return $this;
    }

    public function filterOptionsCallback($callback): Column
    {
        $this->filter_options_callback = $callback;
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

    public function canSee($value): Column
    {
        $this->visible = $value;
        return $this;
    }
}

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
    public $filter_callback;
    public $filterOptionsLimit;

    public static function make($name, $label = null): Column
    {
        return new static($name, $label);
    }

    public function __construct($name, $label = null)
    {
        $this->name = $name;
        $this->label = $label ? $label : $name;
        $this->action = fn ($row) => $row->{$name};
    }

    public function name($val): Column
    {
        $this->name = $val;
        return $this;
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
        $this->filter_options = array_map(function ($row) {
            if (is_array($row) && array_key_exists("value", $row) && array_key_exists("label", $row)) {
                return $row;
            }
            return ["value" => $row, "label" => $row];
        }, $options);
        return $this;
    }

    public function filterOptionsCallback($callback): Column
    {
        $this->filter_options_callback = $callback;
        return $this;
    }

    public function filterCallback($callback): Column
    {
        $this->filter_callback = $callback;
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

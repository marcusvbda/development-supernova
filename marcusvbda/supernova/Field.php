<?php

namespace marcusvbda\supernova;

class Field
{
    public $field;
    public $label;
    public $resource;
    public $noData;
    public $uploadDisk;
    public $model;
    public $query;
    public $limit = 1;
    public $multiple = false;
    public $detailCallback;
    public $mask = "";
    public $type = "text";
    public $rules = [];
    public $messages = [];
    public $option_keys = ['value' => 'id', 'label' => 'name'];
    public $options_callback;
    public $options = [];
    public $previewCallback = null;
    public $visible = true;

    public function isNamespace($val)
    {
        return strpos($val, "\\") !== false;
    }

    public static function make($field, $label = null): Field
    {
        return new static($field, $label);
    }

    public function __construct($field, $label = null)
    {
        $this->field = $field;
        if (!$this->isNamespace($field)) {
            $this->label = $label ? $label : $field;
            $this->noData = config("supernova.placeholder_no_data", "<span>   -   </span>");
            $this->detailCallback = fn ($entity) => @$entity?->{$this->field} ?? $this->noData;
        } else {
            $this->module = $field;
            $parentModule = app()->make($field);
            $this->field = $parentModule->id();
            $this->type = FIELD_TYPES::MODULE->value;
            $this->query = fn ($row) => $row->{$this->field}();
        }
    }

    public function mask($mask): Field
    {
        $this->mask = $mask;
        return $this;
    }

    public function type($type, $relation = null): Field
    {
        $this->type = is_string($type) ? $type : @$type->value;

        if ($this->type === FIELD_TYPES::TEXT->value) {
            $this->detailCallback = fn ($entity) => @$entity?->{$this->field} ?? $this->noData;
        } elseif ($this->type === FIELD_TYPES::SELECT->value) {
            $this->detailCallback = function ($entity)  use ($relation) {
                if (!$this->model) {
                    if (!$this->multiple) {
                        $value = @$entity?->{$this->field} ?? null;
                        $option = collect($this->options)->first(fn ($row) => $row["value"] == $value);
                        return $option ? $option["label"] : $this->noData;
                    } else {
                        $value = @$entity?->{$this->field} ?? [];
                        $valueContent = collect($this->options)->filter(fn ($row) => in_array($row["value"], $value))->map(fn ($row) => $row["label"])->implode(", ");
                        return $valueContent ? $valueContent : $this->noData;
                    }
                } else {
                    if (!$this->multiple) {
                        $value = @$entity?->{$relation ? $relation : $this->field} ?? null;
                        if (!$value) return $this->noData;
                        $valueContent = @$value?->{data_get($this->option_keys, 'label')} ?? null;
                        return $valueContent ? $valueContent : $this->noData;
                    } else {
                        $value = @$entity?->{$relation ? $relation : $this->field} ?? [];
                        if (count($value) == 0) return $this->noData;
                        $valueContent = $value->map(fn ($row) => $row->{data_get($this->option_keys, 'label')})->implode(", ");
                        return $valueContent ? $valueContent : $this->noData;
                    }
                }
            };

            $this->options_callback = function () {
                return $this->model->orderBy(data_get($this->option_keys, 'label'), "asc")->get()->map(function ($row) {
                    return ["value" => $row->{data_get($this->option_keys, 'value')}, "label" => $row->{data_get($this->option_keys, 'label')}];
                })->toArray();
            };
        } elseif ($this->type === FIELD_TYPES::UPLOAD->value) {
            $this->uploadDisk = $relation ? $relation : config("filesystems.default");
            $this->previewCallback = fn ($file) => $file->temporaryUrl();
        }
        return $this;
    }

    public function preview($callback): Field
    {
        if (is_callable($callback)) {
            $this->previewCallback = $callback;
        } else {
            if ($callback === UPLOAD_PREVIEW::AVATAR) {
                $this->previewCallback = function ($file) {
                    $url = $file->temporaryUrl();
                    return <<<BLADE
                        <img src="$url" class="w-40 h-40 rounded border border-gray-300"/>
                    BLADE;
                };
            }
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

    public function multiple($limit = INF): Field
    {
        $this->multiple = true;
        $this->limit = $limit;
        return $this;
    }
}

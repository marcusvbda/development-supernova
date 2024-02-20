<?php

namespace marcusvbda\supernova;

use  Illuminate\View\View;

class Module
{
    public function model(): string
    {
        return "your model here ...";
    }

    public function getCacheQtyKey(): string
    {
        return 'qty:' . $this->id();
    }

    public function clearCacheQty(): void
    {
        cache()->forget($this->getCacheQtyKey());
    }

    public function getCachedQty(): int
    {
        $cacheTime = 60 * 24;
        return cache()->remember($this->getCacheQtyKey(), $cacheTime, function () {
            return $this->makeModel()->count();
        });
    }

    public function title($page): string
    {
        $name = $this->name();
        return match ($page) {
            'index' =>  data_get($name, 1, $this->id()),
            'details' =>  'Detalhes de ' . strtolower(data_get($this->name(), 0)),
            default => $this->id()
        };
    }

    public function id(): string
    {
        $name = class_basename(get_class($this));
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $name));
    }

    public function makeModel()
    {
        $query = app()->make($this->model());
        //custom conditions
        return $query;
    }

    public function canViewIndex(): bool
    {
        return true;
    }

    public function details($entity): View
    {
        $module = $this;
        return view("supernova::modules.details", compact("module", "entity"));
    }

    public function index(): View
    {
        $module = $this;
        return view("supernova::modules.index", compact("module"));
    }

    public function name(): array
    {
        $id = $this->id();
        $singular = ucfirst((substr($id, -1) === 's') ? substr($id, 0, -1) : $id);
        $plural = ucfirst((substr($id, -1) === 's') ? $id : $id . 's');
        return [$singular, $plural];
    }

    public function subMenu(): ?string
    {
        return null;
    }

    public function menu(): string
    {
        $sub = $this->subMenu();
        $menu = $this->name()[1];
        $url = route("supernova.modules.index", ["module" => strtolower($this->id())]);
        return $sub ? "$sub.$menu{href='$url'}" : "$menu{href='$url'}";
    }

    public function metrics(): array
    {
        $moduleId = $this->id();
        $cards[] = <<<BLADE
        @livewire('supernova::counter-card',[
                'module' => '$moduleId',
            ])
        BLADE;
        return $cards;
    }

    public function dashboardMetrics(): array
    {
        return $this->metrics();
    }

    public function canCreate(): bool
    {
        return true;
    }

    public function dataTable(): array
    {
        $tableColumns = $this->getTableColumns();
        $columns = [];
        foreach ($tableColumns as $column) {
            $filterType = FILTER_TYPES::TEXT;
            if ($column === "id") $filterType = FILTER_TYPES::NUMBER_RANGE;
            if ($column === "created_at" || $column === "updated_at") $filterType = FILTER_TYPES::DATE_RANGE;
            $columns[] =  Column::make($column)->searchable()->sortable()->filterable($filterType);
        }
        return $columns;
    }

    public function getTableColumns(): array
    {
        $model = $this->makeModel();
        return $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
    }

    public function perPage(): array
    {
        return [10, 25, 50, 100];
    }

    public function defaultSort(): string
    {
        return "id|desc";
    }

    public function canEdit(): bool
    {
        return true;
    }

    public function canEditRow($row): bool
    {
        return true;
    }

    public function canDelete(): bool
    {
        return true;
    }

    public function canDeleteRow($row): bool
    {
        return true;
    }

    public function getDataTableVisibleColumns(): array
    {
        $columns = $this->dataTable();
        $columns = collect($columns)->filter(fn ($column) => $column->visible)->toArray();
        return $columns;
    }

    public function applyFilters($model, $searchText, $filters, $sort): mixed
    {
        $columns = $this->getDataTableVisibleColumns();
        if ($searchText) {
            $model = $model->where(function ($query) use ($columns, $searchText) {
                foreach ($columns as $column) {
                    if ($column->searchable) {
                        $query->orWhere($column->name, "like", "%{$searchText}%");
                    }
                }
            });
        }

        $model = $model->where(function ($query) use ($columns, $filters) {
            foreach ($columns as $column) {
                if ($column->filterable) {
                    $callback = $column->filter_callback;
                    $val = data_get($filters, $column->name, '');
                    if ($val && is_callable($callback)) {
                        $callback($query, $val);
                    } else {
                        if ($column->filter_type == FILTER_TYPES::NUMBER_RANGE->value && (data_get($filters, $column->name . "[0]") || data_get($filters, $column->name . "[1]"))) {
                            $min = data_get($filters, $column->name . "[0]");
                            $max = data_get($filters, $column->name . "[1]");
                            if ($min) $query->where($column->name, ">=", $min);
                            if ($max) $query->where($column->name, "<=", $max);
                        }
                        if ($column->filter_type == FILTER_TYPES::TEXT->value && $val) {
                            $query->where($column->name, "like", "%{$val}%");
                        };
                        if ($column->filter_type == FILTER_TYPES::DATE->value && $val) {
                            $query->whereDate($column->name, $val);
                        };
                        if ($column->filter_type == FILTER_TYPES::DATE_RANGE->value && (data_get($filters, $column->name . "[0]") || data_get($filters, $column->name . "[1]"))) {
                            $min = data_get($filters, $column->name . "[0]");
                            $max = data_get($filters, $column->name . "[1]");
                            if ($min) $query->whereDate($column->name, ">=", $min);
                            if ($max) $query->whereDate($column->name, "<=", $max);
                        }
                        if ($column->filter_type == FILTER_TYPES::SELECT->value && $val) {

                            $query->where(function ($query) use ($column, $val) {
                                foreach ($val as $item) {
                                    $query->orWhere($column->name, data_get($item, 'value'));
                                };
                            });
                        };
                    }
                }
            }
        });

        return $model->orderBy($sort[0], $sort[1]);
    }

    protected function isApi(): bool
    {
        return request()->wantsJson();
    }

    public function createBtnText(): string
    {
        $name = $this->name();
        return "Criar " . strtolower($name[0]);
    }

    public function fields(): array
    {
        $tableColumns = $this->getTableColumns();
        $fields = [];
        foreach ($tableColumns as $column) {
            if (in_array($column, ["id", "created_at", "updated_at"])) continue;
            $fields[] =  Field::make($column)->type(FIELD_TYPES::TEXT);
        }
        return $fields;
    }

    public function getVisibleFieldPanels(): array
    {
        $fieldsWithoutPanel = collect($this->fields())->filter(function ($field) {
            return $field->visible && class_basename(get_class($field)) === "Field";
        })->toArray();
        $panels = [];

        if (count($fieldsWithoutPanel)) {
            $panels[] = Panel::make($this->title("details"))->fields($fieldsWithoutPanel);
        }

        $visiblePanels = collect($this->fields())->filter(function ($panel) {
            $fieldsVisible = count(collect(@$panel->fields ?? [])->filter(function ($field) {
                return $field->visible;
            })->toArray()) > 0;
            return $panel->visible && class_basename(get_class($panel)) === "Panel" && $fieldsVisible;
        })->toArray();

        $panels = array_merge($panels, $visiblePanels);
        return $panels;
    }

    public function processFieldDetail($entity, $field): string
    {
        $detailAction = $field->detailAction;
        if ($detailAction && is_callable($detailAction)) {
            return $detailAction($entity);
        }
        return config("supernova.placeholder_no_data", "<span>   -   </span>");
    }
}

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
            return app()->make($this->model())->count();
        });
    }

    public function title($page): string
    {
        $name = $this->name();
        return match ($page) {
            'index' =>  data_get($name, 1, $this->id()),
            default => $this->id()
        };
    }

    public function id(): string
    {
        $name = class_basename(get_class($this));
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $name));
    }

    public function canViewIndex(): bool
    {
        return true;
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

    public function icon(): ?string
    {
        return <<<HTML
            <svg viewBox="0 0 24 24" fill="none">
                <path opacity="0.5" d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12" stroke-width="1.5"/>
                <path d="M2 14C2 11.1997 2 9.79961 2.54497 8.73005C3.02433 7.78924 3.78924 7.02433 4.73005 6.54497C5.79961 6 7.19974 6 10 6H14C16.8003 6 18.2004 6 19.27 6.54497C20.2108 7.02433 20.9757 7.78924 21.455 8.73005C22 9.79961 22 11.1997 22 14C22 16.8003 22 18.2004 21.455 19.27C20.9757 20.2108 20.2108 20.9757 19.27 21.455C18.2004 22 16.8003 22 14 22H10C7.19974 22 5.79961 22 4.73005 21.455C3.78924 20.9757 3.02433 20.2108 2.54497 19.27C2 18.2004 2 16.8003 2 14Z" stroke-width="1.5"/>
                <path d="M9.5 14.4L10.9286 16L14.5 12" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        HTML;
    }

    public function canCreate(): bool
    {
        return true;
    }

    public function placeholderDatatableColumnNoData(): string
    {
        return <<<HTML
            <span>   -   </span>
        HTML;
    }

    public function dataTable(): array
    {
        $tableColumns = $this->getTableColumns();
        $columns = [];
        foreach ($tableColumns as $column) {
            $filterType = FILTER_TYPES::TEXT;
            if ($column === "id") $filterType = FILTER_TYPES::NUMBER_RANGE;
            if ($column === "created_at" || $column === "updated_at") $filterType = FILTER_TYPES::DATE_RANGE;
            $columns[] =  Column::name($column)->label($column)->searchable()->sortable()->filterable($filterType);
        }
        return $columns;
    }

    public function getTableColumns(): array
    {
        $model = app()->make($this->model());
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
                    $val = data_get($filters, $column->name, '');
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
}

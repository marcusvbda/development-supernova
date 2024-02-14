<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use marcusvbda\supernova\FILTER_TYPES;

class Datatable extends Component
{
    public $module;
    public $icon;
    public $name;
    public $canCreate;
    public $hasResults;
    public $searchText;
    public $filterable;
    public $searchable;
    public $columns;
    public $initialized = false;
    public $hasActions = true;
    public $sort;
    public $itemsPage = [];
    public $perPageOptions = [];
    public $perPage;
    public $canDelete = false;
    public $canEdit = false;
    public $hasPrevCursor = false;
    public $hasNextCursor = false;
    public $cursor = null;
    public $prevCursor = null;
    public $nextCursor = null;
    public $currentPage = 1;
    public $totalPages = 1;
    public $totalResults = 0;
    public $filters  = [];

    public function mount()
    {
        $this->initializeModule();
    }

    private function initializeModule()
    {
        $module = $this->getAppModule();
        $this->icon = $module->icon();
        $this->name = $module->name();
        $this->canCreate = $module->canCreate();
        $this->hasResults = $module->getCachedQty() > 0;
        $this->columns = array_map(function ($row) {
            $row = (array)$row;
            $row["action"] = null;
            return $row;
        }, $module->dataTable());
        $this->searchable = collect($this->columns)->filter(fn ($row) => $row["searchable"])->count() > 0;
        $this->filterable = collect($this->columns)->filter(fn ($row) => $row["filterable"])->count() > 0;
        $this->canDelete = $module->canDelete();
        $this->canEdit = $module->canEdit();
        $this->hasActions = $this->canDelete || $this->canEdit;
    }

    private function getAppModule()
    {
        $application = app()->make(config("supernova.application", Application::class));
        return $application->getModule($this->module);
    }

    public function reloadSort($name, $oldName, $oldDirection)
    {
        $newDirection = "desc";
        $newName = $name;
        if ($oldName == $name) {
            $newDirection = $oldDirection == "desc" ? "asc" : "desc";
        }
        $this->sort = "{$newName}|{$newDirection}";
        $this->cursor = null;
        $this->loadData();
    }

    public function setCursor($cursor, $type)
    {
        $this->cursor = $cursor;
        if ($type == "prev") {
            $this->currentPage--;
        } else {
            $this->currentPage++;
        }
        $this->loadData();
    }

    public function loadData()
    {
        $this->initializeModule();
        $sort = explode("|", $this->sort);
        $module = $this->getAppModule();
        $this->perPageOptions = $module->perPage();
        $columns = $module->dataTable();
        $modelClass = $module->model();
        $model = app()->make($modelClass);
        $this->perPage = in_array($this->perPage, $this->perPageOptions) ? $this->perPage : $this->perPageOptions[0];
        if ($this->searchText) {
            $model = $model->where(function ($query) use ($columns) {
                foreach ($columns as $column) {
                    if ($column->searchable) {
                        $query->orWhere($column->name, "like", "%{$this->searchText}%");
                    }
                }
            });
        }
        $model = $model->where(function ($query) use ($columns) {
            foreach ($columns as $column) {
                if ($column->filterable) {
                    if ($column->filter_type == FILTER_TYPES::MINMAX->value) {
                        $min = data_get($this->filters, $column->name . "[0]");
                        $max = data_get($this->filters, $column->name . "[1]");
                        if ($min) $query->where($column->name, ">=", $min);
                        if ($max) $query->where($column->name, "<=", $max);
                    }
                    if ($column->filter_type == FILTER_TYPES::TEXT->value && @$this->filters[$column->name]) {
                        $query->where($column->name, "like", "%{$this->filters[$column->name]}%");
                    };
                }
            }
            return $query;
        });
        $query = $model->orderBy($sort[0], $sort[1]);
        $total = $query->count();
        $items = $query->cursorPaginate($this->perPage, ['*'], 'cursor', Cursor::fromEncoded($this->cursor));
        $this->hasNextCursor = $items->hasMorePages();
        $this->nextCursor = $this->hasNextCursor  ? $items->nextCursor()->encode() : null;
        $this->hasPrevCursor = $items->previousCursor() != null;
        $this->prevCursor = $this->hasPrevCursor ? $items->previousCursor()->encode() : null;
        $this->itemsPage = $this->processItems($items, $columns);
        $this->totalPages =  ceil($total / $this->perPage);
        $this->totalResults = $total;
        $this->initialized = true;
    }

    public function updated($field)
    {
        if (str_starts_with($field, "filters.") || $field === "searchText") {
            $this->currentPage = 1;
            $this->cursor = null;
            $this->loadData();
        }
    }

    private function processItems($items, $columns): array
    {
        $module = $this->getAppModule();
        $itemsPage = [];
        foreach ($items as $item) {
            $rowColumns = [];
            foreach ($columns as $column) {
                $action = $column->action;
                $rowColumns[$column->name] = $this->executeAction($action, $item);
                $rowColumns["canEdit"] = $module->canEditRow($item) && $this->canEdit;
                $rowColumns["canDelete"] = $module->canDeleteRow($item) && $this->canDelete;
            }
            $itemsPage[] = $rowColumns;
        }

        return $itemsPage;
    }

    private function executeAction($action, $item)
    {
        if (is_callable($action)) {
            $result = @$action($item);
            return Blade::render($result ?? " - ");
        } elseif (is_string($action) || is_numeric($action)) {
            return Blade::render($action);
        }
        return Blade::render(" - ");
    }

    public function clearFilter($field)
    {
        data_set($this->filters, $field, null);
        $this->loadData();
    }

    public function clearSearch()
    {
        $this->searchText = "";
        $this->loadData();
    }

    public function render()
    {
        return view('supernova-livewire-views::datatable.index');
    }
}

<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Illuminate\Pagination\Cursor;
use Illuminate\Support\Facades\Blade;
use Livewire\Component;

class Datatable extends Component
{
    public $module;
    public $icon;
    public $name;
    public $canCreate;
    public $searchText;
    public $filterable;
    public $searchable;
    public $columns;
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
    public $hasItems = false;

    public function mount()
    {
        $this->initializeModule();
    }

    public function updateFilterValue($field, $value, $label, $type)
    {
        if (str_starts_with($type, 'multiple')) {
            $oldValues = data_get($this->filters, $field, []);
            if (!collect($oldValues)->contains(fn ($item) => $item['value'] == $value)) {
                $this->filters[$field][] = ['value' => $value, 'label' => $label];
            }
        } else {
            $this->filters[$field] = $value;
        }
    }

    public function getListeners()
    {
        $listers = [];
        $module = $this->getAppModule();
        $columns = $module->getDataTableVisibleColumns();
        foreach ($columns as $column) {
            if ($column->filterable) {
                $listers["filters[{$column->name}]:changed"] = "updateFilterValue";
            }
        }
        return $listers;
    }

    public function placeholder()
    {
        return view('supernova-livewire-views::skeleton', ['size' => '500px']);
    }

    private function initializeModule()
    {
        $module = $this->getAppModule();
        $this->icon = $module->icon();
        $this->name = $module->name();
        $this->canCreate = $module->canCreate();
        $this->columns = array_map(function ($row) {
            $row = (array)$row;
            $row["action"] = null;
            $row["filter_options"] = null;
            $row["filter_options_callback"] = null;
            return $row;
        }, $module->getDataTableVisibleColumns());
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
        $this->perPage = in_array($this->perPage, $this->perPageOptions) ? $this->perPage : $this->perPageOptions[0];
        $query = $module->applyFilters(app()->make($module->model()), $this->searchText, $this->filters, $sort);
        $total = $query->count();
        $items = $query->cursorPaginate($this->perPage, ['*'], 'cursor', Cursor::fromEncoded($this->cursor));
        $this->hasNextCursor = $items->hasMorePages();
        $this->nextCursor = $this->hasNextCursor  ? $items->nextCursor()->encode() : null;
        $this->hasPrevCursor = $items->previousCursor() != null;
        $this->prevCursor = $this->hasPrevCursor ? $items->previousCursor()->encode() : null;
        $this->itemsPage = $this->processItems($items);
        $this->totalPages =  ceil($total / $this->perPage);
        $this->totalResults = $total;
        $this->hasItems = $total > 0;
        if (!$this->hasItems) {
            $this->hasActions = false;
        }
    }

    public function removeFilter($field, $value)
    {
        $oldValues = data_get($this->filters, $field, []);
        $newValues = collect($oldValues)->filter(fn ($item) => $item['value'] != $value);
        $this->filters[$field] = $newValues->count() > 0 ? $newValues->toArray() : [];
        $this->loadData();
    }

    public function updated($field)
    {
        if (str_starts_with($field, "filters.") || $field === "searchText") {
            $this->currentPage = 1;
            $this->cursor = null;
            $this->loadData();
        }
    }

    private function processItems($items): array
    {
        $module = $this->getAppModule();
        $columns = $module->getDataTableVisibleColumns();
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
        $module = $this->getAppModule();
        if (is_callable($action)) {
            $result = @$action($item);
            return Blade::render($result ? "<span>$result</span>" : $module->placeholderDatatableColumnNoData());
        } elseif (is_string($action) || is_numeric($action)) {
            return Blade::render($action);
        }
        return Blade::render($module->placeholderDatatableColumnNoData());
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
        $this->loadData();
        return view('supernova-livewire-views::datatable.index');
    }

    public function deleteRow($id)
    {
        dd("delete row", $id);
    }
}

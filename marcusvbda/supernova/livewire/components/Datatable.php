<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Livewire\Component;

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
    public function mount()
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
        $this->loadData();
    }

    public function loadData()
    {
        $module = $this->getAppModule();
        // $this->hasResults = $module->getCachedQty() > 0;
        $sort = explode("|", $this->sort);
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
        $query = $model->orderBy($sort[0], $sort[1]);
        $items = $query->paginate($this->perPage);
        $this->itemsPage = $this->processItems($items, $columns);
        $this->initialized = true;
    }

    public function updated()
    {
        $this->loadData();
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
            $result = $action($item);
            if ($result !== null && $result !== '') {
                return $result;
            }
        } elseif (is_string($action) || is_numeric($action)) {
            return $action;
        }
        return " - ";
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

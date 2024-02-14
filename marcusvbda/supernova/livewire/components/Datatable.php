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
    public $searchable;
    public function mount()
    {
        $application = app()->make(config("supernova.application", Application::class));
        $module = $application->getModule($this->module);
        $this->icon = $module->icon();
        $this->name = $module->name();
        $this->canCreate = $module->canCreate();
        $this->hasResults = $module->getCachedQty() > 0;
        $this->searchable = true;
    }

    public function clearSearch()
    {
        $this->searchText = "";
    }

    public function render()
    {
        return view('supernova-livewire-views::datatable.index');
    }
}

<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Livewire\Component;
// use ResourcesHelpers;

class CounterCard extends Component
{
    public $module;
    public $readyToLoad = false;

    public function redirectTo()
    {
        return redirect(route("supernova.modules.index", $this->module));
    }

    public function loadData()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        $application = app()->make(config("supernova.application", Application::class));
        $module = $application->getModule($this->module);

        $content = <<<HTML
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        HTML;
        $name = $module->name()[1];
        $actions = "";
        if ($this->readyToLoad == true) {
            $content = $module->getCachedQty();
            $cardCounterReloadTime = $application->cardCounterReloadTime();
            $actions = "wire:poll.{$cardCounterReloadTime}s wire:click='redirectTo'";
        }
        return <<<BLADE
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full cursor-pointer"  wire:init="loadData" $actions>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-50 mb-3">
                    $name
                </h2>
                <p class="text-4xl font-bold text-gray-800 dark:text-gray-50 mb-2">
                    $content
                </p>
            </div>
        BLADE;
    }
}

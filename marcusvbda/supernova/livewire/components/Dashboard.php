<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Livewire\Component;

class Dashboard extends Component
{
    private function renderCounters($counters)
    {
        $columns = implode("", $counters);
        $content = <<<HTML
            <div class="grid lg:grid-cols-4 md:grid-cols-3 gap-3">
                $columns
            </div>
        HTML;
        return $content;
    }

    public function render()
    {
        $application = app()->make(config("supernova.application", Application::class));
        $metrics = $application->dashboardContent();
        $counterContent = $this->renderCounters(data_get($metrics, "counters", []));

        return <<<BLADE
           <section class="flex flex-col gap-8">
                $counterContent
            </section>
        BLADE;
    }
}

<?php

namespace marcusvbda\supernova;

use App\Http\Supernova\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Livewire\Livewire;
use marcusvbda\supernova\components\Datatable;

class SupernovaServiceProvider extends ServiceProvider
{
    public function boot(Router $router): void
    {
        $novaApp = app()->make(config("supernova.application", Application::class));
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views/', 'supernova');
        $this->publishes([
            'config.php' => config_path() . "/supernova.php",
        ]);
        $router->aliasMiddleware('supernova-default-middleware',  fn ($request, $next) => $novaApp->middleware($request, $next));
    }

    public function register(): void
    {
        $this->registerLivewireComponents();
    }

    protected function registerLivewireComponents()
    {
        Livewire::component('supernova::datatable', Datatable::class);
    }
}

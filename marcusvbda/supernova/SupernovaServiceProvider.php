<?php

namespace marcusvbda\supernova;

use App\Http\Supernova\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Livewire\Livewire;

class SupernovaServiceProvider extends ServiceProvider
{
    private $novaApp;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->novaApp = new Application();
    }

    public function boot(Router $router): void
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views/', 'supernova');
        $this->loadViewsFrom(__DIR__ . '/livewire/views/', 'supernova-livewire-views');
        $this->publishes([
            'config.php' => config_path() . "/supernova.php",
        ]);
        $router->aliasMiddleware('supernova-default-middleware',  fn ($request, $next) => $this->novaApp->middleware($request, $next));
    }

    public function register(): void
    {
        $this->registerLivewireComponents();
    }

    protected function registerLivewireComponents()
    {
        Livewire::component('supernova::navbar', $this->novaApp->navbar());
        Livewire::component('supernova::datatable', $this->novaApp->datatable());
        Livewire::component('supernova::details', $this->novaApp->details());
        Livewire::component('supernova::select-field', $this->novaApp->selectField());
        Livewire::component('supernova::login', $this->novaApp->loginForm());
        Livewire::component('supernova::breadcrumb', $this->novaApp->breadcrumb());
        Livewire::component('supernova::counter-card', $this->novaApp->counterCard());
        Livewire::component('supernova::dashboard', $this->novaApp->dashboard());
        Livewire::component('supernova::alerts', $this->novaApp->alerts());
        Livewire::component('supernova::crud', $this->novaApp->crud());
    }
}

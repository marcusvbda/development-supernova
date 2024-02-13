<?php

namespace marcusvbda\supernova;

use App\Http\Controllers\Controller;
use App\Http\Supernova\Application;
use Auth;
use Illuminate\View\View;

class ModulesController extends Controller
{
    private $application;
    public  function __construct()
    {
        $this->application = app()->make(config("supernova.application", Application::class));
    }

    public function index($module): View
    {
        $module = $this->application->getModule($module);
        if (!$module->canViewIndex()) abort(403);
        return $module->index();
    }

    public function dashboard(): View
    {
        $this->application = app()->make(config("supernova.application", Application::class));
        return view("supernova::dashboard");
    }

    public function login()
    {
        Auth::logout();
        $redirect = request()->get("redirect") ?? "/";
        return view("supernova::auth.login", compact("redirect"));
    }
}

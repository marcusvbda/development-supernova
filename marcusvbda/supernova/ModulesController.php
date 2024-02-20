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

    public function edit($module, $id)
    {
        $module = $this->application->getModule($module);
        if (!$module->canEdit()) abort(403);
        $target = $module->makeModel()->findOrFail($id);
        dd("edit page", $target);
    }

    public function create($module): View
    {
        $module = $this->application->getModule($module);
        if (!$module->canCreate()) abort(403);
        return $module->create();
    }

    public function details($module, $id): View
    {
        $module = $this->application->getModule($module);
        if (!$module->canViewIndex()) abort(403);
        $target = $module->makeModel()->findOrFail($id);
        return $module->details($target);
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

    public function logout()
    {
        Auth::logout();
        return redirect()->back();
    }

    public function login()
    {
        if (Auth::check()) return redirect()->route("supernova.home");
        $redirect = request()->get("redirect") ?? "/";
        return view("supernova::auth.login", compact("redirect"));
    }
}

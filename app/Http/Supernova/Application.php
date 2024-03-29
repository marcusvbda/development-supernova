<?php

namespace App\Http\Supernova;

use App\Http\Supernova\Modules\AccessGroups;
use App\Http\Supernova\Modules\Competences;
use App\Http\Supernova\Modules\Customers;
use App\Http\Supernova\Modules\Permissions;
use App\Http\Supernova\Modules\PermissionTypes;
use App\Http\Supernova\Modules\Projects;
use App\Http\Supernova\Modules\Squads;
use App\Http\Supernova\Modules\Teams;
use App\Http\Supernova\Modules\Users;
use marcusvbda\supernova\Application as SupernovaApplication;
use Auth;

class Application extends SupernovaApplication
{
    public function darkMode(): bool
    {
        return Auth::check() && Auth::user()->dark_mode ? true : false;
    }

    public function logo(): string
    {
        if ($this->darkMode()) {
            return asset("images/logo-white.svg");
        }
        return asset("images/logo.svg");
    }

    public function icon(): string
    {
        return asset("images/favicon.png");
    }

    public function dashboardMetrics()
    {
        $modules = ["projects", "customers", "squads", "teams"];
        $cards = [];

        foreach ($modules as $module) {
            $cards = array_merge($cards, $this->getModule($module)->dashboardMetrics());
        }

        return $cards;
    }

    public function modules(): array
    {
        return [
            // times
            Competences::class,
            Squads::class,
            Teams::class,
            Users::class,

            //demandas
            Customers::class,
            Projects::class,

            //configurações
            AccessGroups::class,
            PermissionTypes::class,
            Permissions::class,
        ];
    }

    public function menuUserNavbar(): array
    {
        $menu = parent::menuUserNavbar();
        $items = data_get($menu, "items", []);
        $menuItems = [
            "Profile" => route('supernova.modules.details', ['module' => 'users', 'id' => Auth::user()->id]),
        ];
        $menu["items"] = [...$menuItems, ...$items];
        return $menu;
    }
}

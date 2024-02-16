<?php

namespace App\Http\Supernova;

use marcusvbda\supernova\Application as SupernovaApplication;
use Auth;

class Application extends SupernovaApplication
{
    public function darkMode(): bool
    {
        // return true;
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

    public function dashboardCards()
    {
        $modules = ["projects", "customers", "squads", "teams"];
        $cards = [];

        foreach ($modules as $module) {
            $cards = array_merge($cards, $this->getModule($module)->dashboardCards());
        }

        return $cards;
    }

    public function menuItems(): array
    {
        $items = parent::menuItems();
        return [
            "Dashboard" => data_get($items, "Dashboard"),
            "Time" => data_get($items, "Time"),
            "Demandas" => data_get($items, "Demandas"),
            "Configurações" => data_get($items, "Configurações"),
        ];
    }
}

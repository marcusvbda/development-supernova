<?php

namespace App\Http\Supernova;

use marcusvbda\supernova\Application as SupernovaApplication;

class Application extends SupernovaApplication
{
    public function darkMode(): bool
    {
        return false;
    }

    public function logo(): string
    {
        return asset("images/logo.svg");
    }

    public function icon(): string
    {
        return asset("images/favicon.png");
    }
}

<?php

namespace App\Http\Supernova;

use marcusvbda\supernova\Application as SupernovaApplication;

class Application extends SupernovaApplication
{
    public function icon(): string
    {
        return "favicon.png";
    }
}

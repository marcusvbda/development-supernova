<?php

namespace App\Http\Supernova\Modules;

use App\Models\Squad;
use marcusvbda\supernova\Module;

class Squads extends Module
{
    public function subMenu(): string
    {
        return "Time";
    }

    public function model(): string
    {
        return Squad::class;
    }
}

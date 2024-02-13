<?php

namespace App\Http\Supernova\Modules;

use App\Models\Squad;
use marcusvbda\supernova\Module;

class Squads extends Module
{
    public function subMenu(): string
    {
        return "Times";
    }

    public function model(): string
    {
        return Squad::class;
    }
}

<?php

namespace App\Http\Supernova\Modules;

use App\Models\Team;
use marcusvbda\supernova\Module;

class Teams extends Module
{
    public function subMenu(): string
    {
        return "Time";
    }

    public function name(): array
    {
        return ['Time', 'Times'];
    }

    public function model(): string
    {
        return Team::class;
    }
}

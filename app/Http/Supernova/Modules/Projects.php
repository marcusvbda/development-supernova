<?php

namespace App\Http\Supernova\Modules;

use App\Models\Team;
use marcusvbda\supernova\Module;

class Projects extends Module
{
    public function subMenu(): string
    {
        return "Demandas";
    }

    public function name(): array
    {
        return ['Projeto', 'Projetos'];
    }

    public function model(): string
    {
        return Team::class;
    }
}

<?php

namespace App\Http\Supernova\Modules;

use App\Models\Squad;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\FILTER_TYPES;
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

    public function dataTable(): array
    {
        $columns[] = Column::name("id")->label("Id")->width("200px")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::name("name")->label("Nome")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        return $columns;
    }
}

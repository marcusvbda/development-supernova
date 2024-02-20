<?php

namespace App\Http\Supernova\Modules;

use App\Models\Project;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FILTER_TYPES;
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
        return Project::class;
    }

    public function dataTable(): array
    {
        $columns[] = Column::make("id", "Id")->width("200px")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::make("name", "Nome")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        return $columns;
    }

    public function fields(): array
    {
        return [
            Field::make("name", "Name"),
        ];
    }
}

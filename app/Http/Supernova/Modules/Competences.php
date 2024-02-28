<?php

namespace App\Http\Supernova\Modules;

use App\Models\Competence;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;

class Competences extends Module
{
    public function subMenu(): string
    {
        return "Time";
    }

    public function name(): array
    {
        return ['Competência', 'Competências'];
    }

    public function model(): string
    {
        return Competence::class;
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

    public function fields($row): array
    {
        return [
            Field::make("name", "Nome")->rules(["required"]),
            Field::make(Skills::class)
        ];
    }
}

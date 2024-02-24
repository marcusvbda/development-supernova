<?php

namespace App\Http\Supernova\Modules;

use App\Models\Team;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FILTER_TYPES;
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

    public function dataTable(): array
    {
        $columns[] = Column::make("id", "Id")->width("200px")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::make("name", "Nome")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        $columns[] = Column::make("created_at", "Criado em ...")
            ->searchable()->sortable()
            ->callback(fn ($row) => $row->created_at?->format("d/m/Y - H:i:s"))
            ->filterable(FILTER_TYPES::DATE_RANGE);
        return $columns;
    }

    public function fields(): array
    {
        return [
            Field::make("name", "Nome")->rules(["required"])
        ];
    }
}

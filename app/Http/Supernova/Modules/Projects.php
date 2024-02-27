<?php

namespace App\Http\Supernova\Modules;

use App\Models\Customer;
use App\Models\Project;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FIELD_TYPES;
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
        $columns[] = Column::make("created_at", "Criado em ...")
            ->searchable()->sortable()
            ->callback(fn ($row) => $row->created_at?->format("d/m/Y - H:i:s"))
            ->filterable(FILTER_TYPES::DATE_RANGE);
        return $columns;
    }

    public function fields(): array
    {
        return [
            Field::make("name", "Nome")->rules(["required"], ["required" => "O campo nome é obrigatório"]),
            Field::make("customer_id", "Cliente")->type(FIELD_TYPES::SELECT, 'customer')
                ->options(Customer::class)
                ->rules(["required"], ["required" => "O campo tipo é obrigatório"]),
            Field::make("board", "URL do Board")->rules(["nullable", "url"]),
        ];
    }
}

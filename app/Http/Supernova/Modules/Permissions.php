<?php

namespace App\Http\Supernova\Modules;

use App\Models\Permission;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;
use marcusvbda\supernova\Panel;

class Permissions extends Module
{
    public function subMenu(): string
    {
        return "Configurações";
    }

    public function name(): array
    {
        return ['Permissão', 'Permissões'];
    }

    public function model(): string
    {
        return Permission::class;
    }

    public function dataTable(): array
    {
        $columns[] = Column::make("id", "Id")->width("200px")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::make("name", "Nome")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        $columns[] = Column::make("type", "Tipo")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::SELECT, 3)
            ->filterOptions(Permission::$types);
        $columns[] = Column::make("created_at", "Criado em ...")
            ->searchable()->sortable()
            ->callback(fn ($row) => $row->created_at?->format("d/m/Y - H:i:s"))
            ->filterable(FILTER_TYPES::DATE_RANGE);
        return $columns;
    }

    public function fields(): array
    {
        return [
            Field::make("tete", "teste")->canSee(true),

            Panel::make("Informações Básicas")->fields([
                Field::make("name", "Nome"),
                Field::make("teste", "teste2")
                // Column::make("type", "Tipo")->rules("required")->type("select")->options(Permission::$types)
            ])
        ];
    }
}

<?php

namespace App\Http\Supernova\Modules;

use App\Models\Permission;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;

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
        $columns[] = Column::name("id")->label("Id")->width("200px")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::name("name")->label("Nome")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        $columns[] = Column::name("type")->label("Tipo")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::SELECT, 3)
            ->filterOptionsCallback(function ($model) {
                return $model->groupBy('type')->select(["type as value", "type as label"])->get()->toArray();
            });
        return $columns;
    }
}

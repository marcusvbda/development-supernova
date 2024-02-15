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
        $columns[] = Column::name("id")->label("Id")->searchable()->sortable()->width("200px")->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::name("name")->label("Nome")->searchable()->sortable()->filterable(FILTER_TYPES::TEXT);
        $columns[] = Column::name("type")->label("Tipo")->searchable()->sortable()->filterable(FILTER_TYPES::SELECT);
        $columns[] = Column::name("created_at")->label("Criado em ...")->searchable()->sortable()->filterable(FILTER_TYPES::DATE_RANGE);
        return $columns;
    }
}

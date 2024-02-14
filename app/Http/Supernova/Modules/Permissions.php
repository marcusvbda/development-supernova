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
        $columns[] = Column::name("id")->searchable()->sortable()->filterable(FILTER_TYPES::MINMAX);
        $columns[] = Column::name("name")->searchable()->sortable()->filterable(FILTER_TYPES::TEXT);
        return $columns;
    }
}

<?php

namespace App\Http\Supernova\Modules;

use App\Models\AccessGroup;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;

class AccessGroups extends Module
{
    public function subMenu(): string
    {
        return "Configurações";
    }

    public function name(): array
    {
        return ['Grupo de acesso', 'Grupos de acesso'];
    }

    public function model(): string
    {
        return AccessGroup::class;
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

    // public function fields(): array
    // {
    //     return [
    //         Field::make("name", "Name"),
    //         Field::make("key", "Chave"),
    //         Field::make("type", "Tipo")->type(FIELD_TYPES::SELECT)
    //             ->options(PermissionType::class)
    //     ];
    // }

    // public function canDelete(): bool
    // {
    //     return false;
    // }

    // public function canEdit(): bool
    // {
    //     return false;
    // }

    // public function canCreate(): bool
    // {
    //     return false;
    // }
}

<?php

namespace App\Http\Supernova\Modules;

use App\Models\AccessGroup;
use App\Models\Permission;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;
use Auth;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FIELD_TYPES;

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

    public function fields(): array
    {
        return [
            Field::make("name", "Nome")->rules(["required"]),
            Field::make("permissions", "Permissões")->type(FIELD_TYPES::SELECT)
                ->options(Permission::class, 'permissions')
                ->multiple()
            // Field::make("permissions", "Permissões")
            //     ->resource(Permissions::class)
            //     ->query(fn ($row) => $row->permissions())
        ];
    }

    public function canDelete(): bool
    {
        return Auth::user()->role === "root";
    }

    public function canEdit(): bool
    {
        return Auth::user()->role === "root";
    }

    public function canCreate(): bool
    {
        return Auth::user()->role === "root";
    }
}

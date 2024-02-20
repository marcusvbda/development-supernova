<?php

namespace App\Http\Supernova\Modules;

use App\Models\Permission;
use App\Models\PermissionType;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FIELD_TYPES;
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
        $columns[] = Column::make("id", "Id")->width("200px")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::make("name", "Nome")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        $columns[] = Column::make("type", "Tipo")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::SELECT, 3)
            ->filterOptions(PermissionType::class);
        return $columns;
    }

    public function fields(): array
    {
        return [
            Field::make("name", "Name"),
            Field::make("key", "Chave"),
            Field::make("type", "Tipo")->type(FIELD_TYPES::SELECT)
                ->options(PermissionType::class)
        ];
    }

    public function canDelete(): bool
    {
        return true;
    }

    public function canEdit(): bool
    {
        return false;
    }

    public function canCreate(): bool
    {
        return false;
    }
}

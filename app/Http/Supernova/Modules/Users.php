<?php

namespace App\Http\Supernova\Modules;

use App\Models\User;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;

class Users extends Module
{
    public function subMenu(): string
    {
        return "Time";
    }

    public function name(): array
    {
        return ['Usuário', 'Usuários'];
    }

    public function model(): string
    {
        return User::class;
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
}

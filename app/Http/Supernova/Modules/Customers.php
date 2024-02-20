<?php

namespace App\Http\Supernova\Modules;

use App\Models\Customer;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;

class Customers extends Module
{
    public function subMenu(): string
    {
        return "Demandas";
    }

    public function name(): array
    {
        return ['Cliente', 'Clientes'];
    }

    public function model(): string
    {
        return Customer::class;
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
            Field::make("name", "Name"),
        ];
    }
}

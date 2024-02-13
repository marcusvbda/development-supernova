<?php

namespace App\Http\Supernova\Modules;

use App\Models\Customer;
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
}

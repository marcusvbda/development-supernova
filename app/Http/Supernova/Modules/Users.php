<?php

namespace App\Http\Supernova\Modules;

use App\Models\User;
use marcusvbda\supernova\Module;

class Users extends Module
{
    public function subMenu(): string
    {
        return "Time";
    }

    public function name(): array
    {
        return ['Diwer', 'Diwers'];
    }

    public function model(): string
    {
        return User::class;
    }
}

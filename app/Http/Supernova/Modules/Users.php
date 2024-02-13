<?php

namespace App\Http\Supernova\Modules;

use App\Models\User;
use marcusvbda\supernova\Module;

class Users extends Module
{
    public function subMenu(): string
    {
        return "Times";
    }

    public function name(): array
    {
        return ['Usuário', 'Usuários'];
    }

    public function model(): string
    {
        return User::class;
    }
}

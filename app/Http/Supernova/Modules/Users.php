<?php

namespace App\Http\Supernova\Modules;

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
}

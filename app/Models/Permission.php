<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = "permissions";
    public $guarded = ["created_at"];

    public static $types = ['Grupos de acesso', 'Usuários', 'Squads', 'Clientes'];
}

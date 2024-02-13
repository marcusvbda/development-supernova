<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Squad extends Model
{
    protected $table = "squads";
    public $guarded = ["created_at"];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Squad extends Model
{
    use SoftDeletes;
    protected $table = "squads";
    public $guarded = ["created_at"];
}

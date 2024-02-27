<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = "projects";
    public $guarded = ["created_at"];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

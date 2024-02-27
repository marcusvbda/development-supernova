<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    protected $table = "competences";
    public $guarded = ["created_at"];

    public function skills()
    {
        return $this->hasMany(Skill::class, "competence_id", "id");
    }
}

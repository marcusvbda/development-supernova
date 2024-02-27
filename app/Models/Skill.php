<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $table = "skills";
    public $guarded = ["created_at"];

    public function competences()
    {
        return $this->belongsToMany(Competence::class, "competence_skill", "skill_id", "competence_id");
    }
}

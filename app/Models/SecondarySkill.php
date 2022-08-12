<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondarySkill extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'secondary_skills';

    public function properties()
    {
        return $this->hasMany(SkillProperties::class,'secondary_skill_id','id');
    }

}

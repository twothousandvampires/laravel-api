<?php

namespace App\Models;

use App\Models\SkillProperties;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SecondarySkill;

class Skill extends Model
{
    use HasFactory;

    public $timestamps = FALSE;

    public function properties()
    {
        return $this->hasMany(SkillProperties::class,'skill_id','id');
    }

    public function chields()
    {
        return $this->hasMany(SecondarySkill::class,'main_skill_id','id');
    }
}

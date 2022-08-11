<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SkillProperties;

class Skill extends Model
{
    use HasFactory;
    
    public $timestamps = FALSE;
    public function properties()
    {
        return $this->hasMany(SkillProperties::class,'skill_id','id');
    }

}

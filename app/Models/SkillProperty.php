<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillProperty extends Model
{
    use HasFactory;

    protected $table = 'skill_property';

    public $timestamps = FALSE;

    protected $fillable = ['skill_id','name','type','exp_needed','level', 'max_level'];

    public function parent()
    {
        return $this->belongsTo(GemSkills::class,'skill_id','id');
    }
}

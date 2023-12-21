<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GemSkills extends Model
{
    use HasFactory;

    protected $table = 'gem_skills';

    public $timestamps = FALSE;

    protected $fillable = ['item_id','name','skill_type','exp_needed','level','skill_class'];


    public function children(){
        $this->children = $this->hasMany(SkillProperty::class,'skill_id','id')->get();
        if(!count($this->children)){
            $this->children = [];
        }
        return $this;
    }

}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GemSkills extends Model
{

    protected $table = 'gem_skills';
    public $timestamps = false;
    protected $fillable = ['item_id','name','skill_type','exp_needed','level','skill_class'];
    protected $appends = ['children'];

    public const MAX_SKILL_LEVEL = 10;

    protected static function affect($skill, &$character){
        $stat_and_value = GemSkills::getPassiveStatAndValue($skill->name);
        if(!$stat_and_value){
            return;
        }
        $level = $skill->level;
        $stat = $stat_and_value['stat'];
        $value = $stat_and_value['progress'][$level];
        $character[$stat] += $value;
    }

    protected static function unaffect($skill, &$character){
        $stat_and_value = GemSkills::getPassiveStatAndValue($skill->name);
        if(!$stat_and_value){
            return;
        }
        $level = $skill->level;
        $stat = $stat_and_value['stat'];
        $value = $stat_and_value['progress'][$level];
        $character[$stat] -= $value;
    }

    public static function unaffectPassiveSkillToCharacter(&$item, &$character){
        $skill = $item->skill;
        GemSkills::unaffect($skill, $character);
        foreach ($skill->children as $child){
            GemSkills::unaffect($child, $character);
        }
    }

    public static function affectPassiveSkillToCharacter(&$item, &$character){
        $skill = $item->skill;
        GemSkills::affect($skill, $character);
        foreach ($skill->children as $child){
            GemSkills::affect($child, $character);
        }
    }

    public static function getPassiveStatAndValue($skill_name): array
    {
        return match ($skill_name) {
            'default' => null,
            'stone skin' => [
                'stat' => 'armour',
                'progress' => [
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                    6 => 6,
                    7 => 7,
                    8 => 8,
                    9 => 9,
                    10 => 10,
                ]
            ]
        };
    }

    public function getChildrenAttribute()
    {
        return $this->hasMany(SkillProperty::class,'skill_id','id')->get();
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = true;
    const RARITY= [
        1 => 'common',
        2 => 'uncommon',
        3 => 'rare',
        4 => 'legendary'
    ];

    const QUALITY = [
        1 => 'low',
        2 => 'normal',
        3 => 'good',
        4 => 'masterpiece'
    ];

    const ITEM_TYPE_EQUIP = 1;
    const ITEM_TYPE_GEM = 2;
    const ITEM_TYPE_USED = 3;

    const GEM_CLASS_SORCERY = 2;
    const GEM_CLASS_COMBAT = 1;
    const GEM_CLASS_MOVEMENT = 3;
    const GEM_CLASS_ALL = 4;

    const GEM_TYPE_ACTIVE = 1;
    const GEM_TYPE_PASSIVE = 2;
    const GEM_TYPE_ALL = 3;

    const EQUIP_CLASS_ACCESSORY = 3;


    protected $fillable = ['char_id','name','slot','type','rarity'];

    protected $appends = ['details', 'props', 'skill'];

    public function getDetailsAttribute()
    {
        if($this->type == self::ITEM_TYPE_EQUIP){
            return $this->hasOne(EquipDetail::class,'item_id','id')->first();
        }
        else if($this->type == self::ITEM_TYPE_GEM){
            return $this->hasOne(GemDetail::class,'item_id','id')->first();
        }
        else if($this->type == self::ITEM_TYPE_USED){
            return $this->hasOne(UsedDetail::class,'item_id','id')->first();
        }
    }

    public function getPropsAttribute()
    {
        if($this->type == self::ITEM_TYPE_EQUIP){
            return $this->hasMany(Property::class,'item_id','id')->get();
        }
        else if($this->type == self::ITEM_TYPE_GEM){
            return $this->hasMany(GemProperties::class,'item_id','id')->get();
        }
        else{
            return null;
        }
    }

    public function getSkillAttribute()
    {
        if($this->type == self::ITEM_TYPE_GEM){
            return $this->hasOne(GemSkills::class,'item_id','id')->first()->children();
        }
        else{
            return null;
        }
    }

    public function character(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}

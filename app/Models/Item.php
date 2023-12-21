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


    protected $fillable = ['char_id','name','slot','type','rarity'];

    protected $guarded = ['details'];
    public function details(){
        switch ($this->type){
            case self::ITEM_TYPE_USED:
                $this->details = $this->hasOne(UsedDetail::class,'item_id','id')->get();
                break;
            case self::ITEM_TYPE_GEM:
                $this->details = $this->hasOne(GemDetail::class,'item_id','id')->first();
                $this->props = $this->hasMany(GemProperties::class,'item_id','id')->get();
                $this->skill = $this->hasOne(GemSkills::class,'item_id','id')->first()->children();
                break;
            case self::ITEM_TYPE_EQUIP:
                $this->details = $this->hasOne(EquipDetail::class,'item_id','id')->first();
                $this->props = $this->hasMany(Property::class,'item_id','id')->get();
                break;
        }
        return $this;
    }

}

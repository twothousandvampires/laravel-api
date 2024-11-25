<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = true;
    const RARITY = [
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
    const ITEM_TYPE_USED = 3;


    const EQUIP_CLASS_ACCESSORY = 3;
    const EQUIP_CLASS_ARMOUR = 2;
    const EQUIP_CLASS_WEAPON = 1;

    protected $fillable = ['char_id', 'name', 'slot', 'type', 'rarity'];

    protected $appends = ['details', 'props'];

    public function isEquipped(): bool
    {
        return $this->slot <= 8;
    }

    public function getDetailsAttribute()
    {
        if ($this->type == self::ITEM_TYPE_EQUIP) {
            return $this->hasOne(EquipDetail::class, 'item_id', 'id')->first();
        }
        else if ($this->type == self::ITEM_TYPE_USED) {
            return $this->hasOne(UsedDetail::class, 'item_id', 'id')->first();
        }
    }

    public function getPropsAttribute(): ?\Illuminate\Database\Eloquent\Collection
    {
        if ($this->type == self::ITEM_TYPE_EQUIP) {
            return $this->hasMany(Property::class, 'item_id', 'id')->get();
        }
        else {
            return null;
        }
    }

    public function character(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}

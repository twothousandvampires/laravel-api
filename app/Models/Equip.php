<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Equip extends Item
{
    protected $table = 'items';

    public function details()
    {
        return $this->hasOne(EquipDetail::class,'item_id','id');
    }
}

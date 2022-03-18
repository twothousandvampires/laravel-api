<?php

namespace App\Http\Services;
use App\Models\Character;
use App\Models\WeaponList;
use App\Models\Weapon;
use App\Models\WeaponPropertylist;

class InventoryService{

    public function createInventory($char_id){
        $items = Weapon::where('char_id', $char_id)->get();
        return [$items];
    }

}


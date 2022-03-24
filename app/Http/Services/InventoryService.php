<?php

namespace App\Http\Services;
use App\Models\Armour;
use App\Models\Weapon;


class InventoryService{

    public function createInventory($char_id){
        $weapon = Weapon::where('char_id', $char_id)->get()->toArray();
        $armour = Armour::where('char_id', $char_id)->get()->toArray();
        return array_merge($weapon,$armour);
    }

}


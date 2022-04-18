<?php

namespace App\Http\Services;
use App\Models\Armour;
use App\Models\Weapon;
use App\Models\Used;


class InventoryService{

    public function createInventory($char_id){
        $weapon = Weapon::where('char_id', $char_id)->get()->toArray();
        $armour = Armour::where('char_id', $char_id)->get()->toArray();
        $used = Used::where('char_id', $char_id)->get()->toArray();
        return array_merge($weapon, $armour, $used);
    }

    public function getFreeSlots($char_id){

        $weapons_id = Weapon::where('char_id', $char_id)->where('slot_type','inv')->get()->pluck('slot')->toArray();
        $armours_id = Armour::where('char_id', $char_id)->where('slot_type','inv')->get()->pluck('slot')->toArray();
        $used_id = Used::where('char_id', $char_id)->where('slot_type','inv')->get()->pluck('slot')->toArray();

        $ids = array_merge($weapons_id, $armours_id, $used_id);

        $result = [];

        for($i = 0; $i < 20; $i++){
            if(!in_array($i,$ids)){
                $result[] = $i;
            }
        }

        return $result;
    }

}


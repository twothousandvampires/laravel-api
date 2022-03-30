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

    public function getFreeSlots($char_id){

        $weapons_id = Weapon::where('char_id', $char_id)->where('slot_type','inv')->get()->pluck('slot')->toArray();
        $armours_id = Armour::where('char_id', $char_id)->where('slot_type','inv')->get()->pluck('slot')->toArray();

        $ids = array_merge($weapons_id,$armours_id);

        $result = [];

        for($i = 0; $i < 20; $i++){
            if(!in_array($i,$ids)){
                $result[] = $i;
            }
        }

        return $result;
    }

}


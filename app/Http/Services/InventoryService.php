<?php

namespace App\Http\Services;
use App\Models\Armour;
use App\Models\Item;
use App\Models\Used;


class InventoryService{

    public function createInventory($char_id){
        $items = Item::where('char_id',$char_id)->with('properties')->get();
        return $items;
    }

    public function getFreeSlots($char_id){

        $ids = Item::where('char_id', $char_id)->where('slot', '>', 8)->where('slot', '<', 29)->get()->pluck('slot')->toArray();

        for($i = 9; $i < 29; $i++){
            if(!in_array($i,$ids)){
                return $i;
            }
        }
    }

}


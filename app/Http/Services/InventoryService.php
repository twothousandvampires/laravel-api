<?php

namespace App\Http\Services;
use App\Models\Item;


class InventoryService{

    public function getFreeSlots($char_id){

        $ids = Item::where('char_id', $char_id)->where('slot', '>', 8)->where('slot', '<', 29)->get()->pluck('slot')->toArray();

        for($i = 9; $i < 29; $i++){
            if(!in_array($i,$ids)){
                return $i;
            }
        }

        return null;
    }

}


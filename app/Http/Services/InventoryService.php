<?php

namespace App\Http\Services;
use App\Models\Item;

class InventoryService{

    public function getFreeSlots($char_id): ?int
    {

        $ids = Item::where('char_id', $char_id)->where('slot', '>', 8)->where('slot', '<', 33)->get()->pluck('slot')->toArray();

        for($i = 9; $i < 33; $i++){
            if(!in_array($i,$ids)){
                return $i;
            }
        }

        return null;
    }

}


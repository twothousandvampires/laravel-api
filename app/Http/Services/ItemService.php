<?php

namespace App\Http\Services;

use App\Models\EquipDetail;
use App\Models\EquipDetailList;
use App\Models\ItemsList;
use App\Models\Item;
use App\Models\EquipPropertiesList;
use App\Models\NodeContent;
use App\Models\Property;
use App\Models\UsedDetail;
use App\Models\UsedDetailList;
use Illuminate\Http\Request;
use ParagonIE\Sodium\Core\Curve25519\Fe;

class ItemService{

    public $inv_service;

    function __construct()
    {
        $this->inv_service = new InventoryService();
    }

    public function createItemFromTreasure($char_id, $node_content_type){
        if($node_content_type === NodeContent::TREASURE_TYPE_CHEST){
            return $this->createRandomItem($char_id);
        }
        elseif ($node_content_type === NodeContent::TREASURE_TYPE_SCROLL){
            return $this->createRandomScroll($char_id);
        }
    }

    public function useUsed(&$item, $characrer = null){
        $details = UsedDetail::where('item_id', $item->id)->first();
    
        if($details->used_type === 2 && $characrer){
            if($characrer->not_consume_food_chance < mt_rand(0, 100)){
                $details->charges --;
            }
            $characrer->food++;
        }
        else{
            $details->charges --;
        }
        if($details->used_type === 1){
            $characrer->addMana($characrer->mana_after_potion_use);
        }
        if($details->charges <= 0){
            $item->delete();
            $details->delete();
        }
        else{
            $details->save();
        }
        if($characrer){
            $characrer->save();
        }  
     
    }

    private function getQuality(): int
    {
        $r = mt_rand(0, 100);
        if($r <= 2){
            return 4;
        }
        else if($r <= 5){
            return 3;
        }
        else if($r <= 12){
            return 2;
        }
        else{
            return  1;
        }
    }
   
    public function createByName($item_name, $char_id, $slot = false){

        if(!$item_name){
            return false;
        }

        $inventoryService = new InventoryService();

        $item_data = [];

        $item_data['char_id'] = $char_id;

        $item_data['slot'] = $slot ?: $inventoryService->getFreeSlots($char_id);

        $quality = $this->getQuality();

        $item_data['quality'] = $quality;

        $base = ItemsList::where('name', $item_name)
                        ->select('id','name','type','rarity')
                        ->first()
                        ->toArray();

        $item_data = array_merge($item_data, $base);

        $item = Item::create($item_data);

        if($item->type == Item::ITEM_TYPE_EQUIP){

            $detail_base = EquipDetailList::where('item_list_id', $base['id'])->first();

            EquipDetail::create([
                'item_id' => $item->id,
                'equip_type' => $detail_base->equip_type,
                'equip_class' => $detail_base->equip_class,
                'equip_quality' => $quality]);


            $property = EquipPropertiesList::where('item_name', $base['name'])->select('id', ITEM::QUALITY[$quality],'stat','name','sub_type')->get();

            foreach ($property as $prop){
                Property::create([
                    'item_id' => $item->id,
                    'name' => $prop->name,
                    'value' => $prop[ITEM::QUALITY[$quality]],
                    'stat' => $prop->stat,
                    'sub_type' => $prop->sub_type,
                    'prop_list_id' => $prop->id]);
            }

        }
        elseif($item->type === Item::ITEM_TYPE_USED){
                $exist = Item::where('name', $item->name)->where('char_id', $char_id)->where('id' ,'!=',$item->id)->first();
                if($exist){
                    $item->delete();
                    $item = $exist;
                    $details = UsedDetail::where('item_id', $item->id)->first();
                    $details->charges ++;
                    $details->save();
                }
                else{
                    $details = UsedDetailList::where('item_list_id', $base['id'])->first();
                    UsedDetail::create([
                        'item_id' => $item->id,
                        'power' => $details->power,
                        'used_type' => $details->used_type,
                        'server_logic' => $details->server_logic
                    ]);
                }
            }


        return Item::find($item->id);
    }
}

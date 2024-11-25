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
    public function createRandomScroll($char_id){
        $item_data = [];

        if($char_id){
            $item_data['char_id'] = $char_id;
        }

        $item_data['slot'] = $this->inv_service->getFreeSlots($char_id);

        //generate quality
        $quality = $this->getQuality();

        $item_data['quality'] = $quality;

        $base = ItemsList::where('type', Item::ITEM_TYPES_SCROLL)
                        ->inRandomOrder()
                        ->select('name','type','class','subclass','rarity')
                        ->first()
                        ->toArray();

        $item_data = array_merge($item_data, $base);

        $item = Item::create($item_data);

        return Item::find($item->id);
    }
    public function createRandomEquip($char_id = false, $name = null){

        $item_data = [];

        if($char_id){
            $item_data['char_id'] = $char_id;
        }

        $item_data['slot'] = $this->inv_service->getFreeSlots($char_id);

        $quality = $this->getQuality();

        $item_data['quality'] = $quality;

        $base = ItemsList::where('type', Item::ITEM_TYPE_EQUIP)
                ->inRandomOrder()
                ->select('id','name', 'type', 'rarity')
                ->first()
                ->toArray();


        $item_data = array_merge($item_data, $base);

        $item = Item::create($item_data);

        $detail_base = EquipDetailList::where('item_list_id',$base['id'])->first();

        $datails = EquipDetail::create([
            'item_id' => $item->id,
            'equip_type' => $detail_base->equip_type,
            'equip_class' => $detail_base->equip_class,
            'equip_quality' => $quality]);


        $property = EquipPropertiesList::where('item_name', $base['name'])->select(ITEM::QUALITY[$quality], 'stat' , 'name')->get();

        foreach ($property as $prop){
            Property::create(['item_id' => $item->id,
                              'name' => $prop->name,
                              'value' => $prop[ITEM::QUALITY[$quality]],
                              'stat' => $prop->stat]);
        }

        return Item::find($item->id);
    }

    public function createRandomUsed($char_id = false){
        $item_data = [];
        if($char_id){
            $item_data['char_id'] = $char_id;
        }
        $item_data['slot'] = min($this->inv_service->getFreeSlots($char_id));
        $base = ItemsList::inRandomOrder()->where('type','used')->select('name','type','class','price','img_path','subclass')->first()->toArray();
        $item_data = array_merge($item_data, $base);

        $item = Item::create($item_data);

        $property = BookPropertiesList::where('item_name', $base['name'])->get();

        foreach ($property as $prop){
            Property::create(['item_id' => $item->id,
                'name' => $prop->name,
                'value' => $prop->exp,
                'stat' => $prop->skill]);
        }

        return Item::with('properties')->find($item->id);
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

                $details = UsedDetailList::where('item_list_id', $base['id'])->first();
                UsedDetail::create([
                    'item_id' => $item->id,
                    'power' => $details->power,
                    'used_type' => $details->used_type,
                    'server_logic' => $details->server_logic
                ]);
            }


        return Item::find($item->id);
    }

    public function createRandomItem($char_id = false){


        return $this->createRandomEquip($char_id);

    }

    public function use(Request $request, $item, $character){
        switch ($item->class){
            case 'book':
                $skill_name = $item->properties[$request->option - 1]->stat;
                $skill_to_learn = Skill::where('char_id', $character->id)->where('name', $skill_name)->first();
                if(!$skill_to_learn){
                    $base = SkillsList::where('name', $skill_name)->first();
                    $skill = new Skill();
                    $skill->char_id = $character->id;
                    $skill->name = $base->name;
                    $skill->level = 1;
                    $skill->type = $base->type;
                    $skill->subtype = $base->subtype;
                    $skill->description = $base->description;
                    $skill->img_path = $base->img_path;

                    $skill->save();

                    $props = SkillPropertiesList::where('skill_name', $skill->name)->get();

                    foreach($props as $prop){
                        $new_prop = new SkillProperties();
                        $new_prop->increased_stat = $prop->increased_stat;
                        $new_prop->chance = $prop->chance;
                        $new_prop->min_value = $prop->min_value;
                        $new_prop->max_value = $prop->max_value;
                        $new_prop->increased_value = $prop->increased_value;
                        $new_prop->skill_name = $prop->skill_name;
                        $new_prop->progression_per_level = $prop->progression_per_level;
                        $new_prop->skill_id = $skill->id;
                        $new_prop->order = $prop->order;
                        $new_prop->save();
                    }

                    $chields = SecondarySkillsList::where('main_skill_name' ,$skill->name)->get();

                    foreach ($chields as $chield){
                        $new_chiled = new SecondarySkill();
                        $new_chiled->main_skill_id = $skill->id;
                        $new_chiled->description = $chield->description;
                        $new_chiled->subtype = $chield->subtype;
                        $new_chiled->img_path = $chield->img_path;
                        $new_chiled->img_path = $chield->img_path;
                        $new_chiled->type = $chield->type;
                        $new_chiled->name = $chield->name;
                        $new_chiled->save();

                        $props = SkillPropertiesList::where('skill_name', $new_chiled->name)->get();

                        foreach($props as $prop){
                            $new_prop = new SkillProperties();
                            $new_prop->increased_stat = $prop->increased_stat;
                            $new_prop->chance = $prop->chance;
                            $new_prop->min_value = $prop->min_value;
                            $new_prop->max_value = $prop->max_value;
                            $new_prop->increased_value = $prop->increased_value;
                            $new_prop->skill_name = $prop->skill_name;
                            $new_prop->progression_per_level = $prop->progression_per_level;
                            $new_prop->secondary_skill_id = $new_chiled->id;
                            $new_prop->order = $prop->order;
                            $new_prop->save();
                        }
                    }

                    return $skill;
                }
                else{
                    $skill_to_learn->level ++;
                    $skill_to_learn->save();
                }
                break;
        }

    }
}

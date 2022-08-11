<?php

namespace App\Http\Services;

use App\Models\ItemsList;
use App\Models\Item;
use App\Models\EquipPropertiesList;
use App\Models\BookPropertiesList;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\SkillsList;
use App\Models\SkillPropertiesList;
use App\Models\SkillProperties;

ini_set('display_errors',1);

class ItemService{

    const RARITY= [
        0 => 'low',
        1 => 'normal',
        2 => 'rare',
        3 => 'masterpiace'
    ];


    public $inv_service;

    function __construct()
    {
        $this->inv_service = new InventoryService();
        $this->skill_service = new SkillService();
    }

    public function createRandomWeapon($char_id = false){

        $item_data = [];
        if($char_id){
            $item_data['char_id'] = $char_id;
        }
        $item_data['slot'] = min($this->inv_service->getFreeSlots($char_id));
        $rarity = self::RARITY[random_int(0,3)];
        $item_data['quality'] = $rarity;
        $base = ItemsList::inRandomOrder()->select('name','type','class','price','img_path','subclass')->first()->toArray();
        $item_data = array_merge($item_data, $base);

        $item = Item::create($item_data);
        $property = EquipPropertiesList::where('item_name', $base['name'])->select($rarity,'stat','name')->get();

        foreach ($property as $prop){
            Property::create(['item_id' => $item->id,
                                'name' => $prop->name,
                                'value' => $prop[$rarity],
                                'stat' => $prop->stat]);
        }

        return Item::with('properties')->find($item->id);
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

    public function createRandomItem($char_id = false){

        $r = random_int(0,100);

//        if($r < 50){
//            return $this->createRandomWeapon($char_id);
//        }
//        else {
//            return $this->createRandomUsed($char_id);
//        }
        return $this->createRandomUsed($char_id);
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
                        $new_prop->prop_name = $prop->prop_name;
                        $new_prop->type = $prop->type;
                        $new_prop->img_path = $prop->img_path;
                        $new_prop->skill_id = $skill->id;
                        $new_prop->order = $prop->order;
                        $new_prop->save();
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

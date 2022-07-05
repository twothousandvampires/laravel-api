<?php

namespace App\Http\Services;

use App\Http\Services\InventoryService;
use App\Models\ArmourList;
use App\Models\Used;
use App\Models\UsedList;
use App\Models\BaseList;
use App\Models\Item;
use App\Models\Armour;
use App\Models\Character;
use App\Models\SkillTreeModel;
use App\Models\Propertylist;
use App\Http\Services\Skill\Active\FireBall;
use Illuminate\Database\Eloquent\Model;
use App\Http\Services\SkillService;

class ItemService{


    public $inv_service;

    function __construct()
    {
        $this->inv_service = new InventoryService();
        $this->skill_service = new SkillService();
    }

    public function createRandomWeapon($char_id = false){

        $item_data = [];
        $item_data['char_id'] = 178;
        $item_data['slot'] = min($this->inv_service->getFreeSlots($char_id));
        $rarity = 'normal';
        $item_data['quality'] = $rarity;
        $base = BaseList::inRandomOrder()->select('name','type','class','slot','price','img_path','property_count')->first()->toArray();
        $item_data = array_merge($item_data, $base);


        $property = Propertylist::where('item_name', $base['name'])->select($rarity,'stat','name')->get()->toArray();

        foreach ($property as $key => $prop){
            $item_data[$key + 1 . '_property_name'] = $prop['name'];
            $item_data[$key + 1 . '_property_value'] = $prop[$rarity];
            $item_data[$key + 1 . '_property_stat'] = $prop['stat'];
        }


        return  Item::create($item_data);


//        $base = BaseList::inRandomOrder()->
//                            limit(1)->
//                            get()->
//                            first();
//
//        $base_props = Propertylist::where('type', 'base')->
//                                    where('item_name', $base->name)->
//                                    select('min_value', 'max_value', 'name', 'type');
//
//        $props = Propertylist::
//                                where('type', '!=', 'base')->
//                               where('item_type','like', '%' . $base->type . '%')->
//                               orWhere('item_class','like', '%' . $base->class . '%')->
//                               orWhere('item_name' ,'like', '%' . $base->name . '%' )->
//                               inRandomOrder()->
//                               limit(3)->
//                                select('min_value', 'max_value', 'name', 'type')->
//                                union($base_props)
//                               ->get();
//
//
//        forEach($props as $prop){
//            $prop->value = random_int($prop->min_value, $prop->max_value);
//            unset($prop->min_value);
//            unset($prop->max_value);
//        }
//
//        $weapon = new Item();
//        $weapon->item_name = $base->name;
//        $weapon->item_type = $base->type;
//        $weapon->item_class = $base->class;
//        if($char_id){
//            $weapon->char_id = $char_id;
//        }
//        $weapon->slot = min($this->inv_service->getFreeSlots($char_id));
//        $item_body = json_decode('{}');
//        $item_body->img_path = $base->img_path;
//        $item_body->min_damage = $base->min_damage;
//        $item_body->max_damage = $base->max_damage;
//        $item_body->attack_speed = $base->attack_speed;
//        $item_body->attack_range = $base->attack_range;
//        $item_body->crit_chance = $base->crit_chance;
////        $item_body->base_props = $base_props;
//        $item_body->props = $props;
//        $weapon->item_body = json_encode($item_body);
//        $weapon->save();
//
//        return $weapon;
    }

    public function createRandomArmour($char_id = false){
        $base = ArmourList::inRandomOrder()->limit(1)->get()->first();


        $base_props = Propertylist::where('type', 'base')->
                                    where('item_name', $base->name)->
                                    select('min_value', 'max_value', 'name', 'type');

        $props = Propertylist::
                            where('type', '!=', 'base')->
                            where('item_type','like', '%' . $base->type . '%')->
                            orWhere('item_class','like', '%' . $base->class . '%')->
                            orWhere('item_name' ,'like', '%' . $base->name . '%' )->
                            inRandomOrder()->
                            limit(3)->
                            select('min_value', 'max_value', 'name', 'type')->
                            union($base_props)
                                ->get();




        forEach($props as $prop){
            $prop->value = random_int($prop->min_value, $prop->max_value);
            unset($prop->min_value);
            unset($prop->max_value);
        }

        $armour = new Item();
        $armour->item_name = $base->name;
        $armour->item_type = $base->type;
        $armour->item_class = $base->class;
        if($char_id){
            $armour->char_id = $char_id;
        }
        $armour->slot = min($this->inv_service->getFreeSlots($char_id));
        $item_body = json_decode('{}');
        $item_body->img_path = $base->img_path;
        $item_body->armour = $base->armour;
        $item_body->evade = $base->evade;
        $item_body->resist = $base->resist;
        $item_body->block = $base->block;
        $item_body->block_count = $base->block_count;
        $item_body->props = $props;
        $armour->item_body = json_encode($item_body);
        $armour->save();

        return $armour;
    }

    public function createRandomUsed($char_id = false){

        $base = UsedList::inRandomOrder()->limit(1)->get()->first();

        $used = new Used();
        $used->name = $base->name;
        $used->type = $base->type;
        $used->class = $base->class;
        $used->value = $base->value;
        $used->affect = $base->affect;
        $used->img_path = $base->img_path;
        if($char_id){
            $used->char_id = $char_id;
        }
        $used->slot_type = 'inv';
        $used->slot = min($this->inv_service->getFreeSlots($char_id));
        $used->save();


        return $used;
    }

    public function createRandomItem($char_id = false){

//        $r = random_int(0,100);
//
//        if($r < 50){
//            return $this->createRandomArmour($char_id);
//        }
//        else if($r < 200){
//            return $this->createRandomWeapon($char_id);
//        }
//        else{
////            return $this->createRandomUsed($char_id);
//        }
        return $this->createRandomWeapon($char_id);
    }

    public function use($item, $character){
        switch ($item->class){
            case 'book':

                $tree = SkillTreeModel::where('char_id',$character->id)->first();


                $tree_body = json_decode($tree->body);


                if(isset($tree_body->{$item->affect})){
                    $tree_body->{$item->affect}->level++;
                }
                else{

                    $tree_body->{$item->affect} = $this->skill_service->create($item->affect);

                }

                $tree->body = json_encode($tree_body);
                $tree->save();
                return json_encode($tree_body->{$item->affect});
        }

    }
}

<?php

namespace App\Http\Services;

use App\Http\Services\InventoryService;
use App\Models\ArmourList;
use App\Models\Used;
use App\Models\UsedList;
use App\Models\WeaponList;
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

        $base = WeaponList::inRandomOrder()->
                            limit(1)->
                            get()->
                            first();

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

        $weapon = new Item();
        $weapon->item_name = $base->name;
        $weapon->item_type = $base->type;
        $weapon->item_class = $base->class;
        if($char_id){
            $weapon->char_id = $char_id;
        }
        $weapon->slot = min($this->inv_service->getFreeSlots($char_id));
        $item_body = json_decode('{}');
        $item_body->img_path = $base->img_path;
        $item_body->min_damage = $base->min_damage;
        $item_body->max_damage = $base->max_damage;
        $item_body->attack_speed = $base->attack_speed;
        $item_body->attack_range = $base->attack_range;
        $item_body->crit_chance = $base->crit_chance;
//        $item_body->base_props = $base_props;
        $item_body->props = $props;
        $weapon->item_body = json_encode($item_body);
        $weapon->save();

        return $weapon;
    }

    public function createRandomArmour($char_id = false){
        $base = ArmourList::inRandomOrder()->limit(1)->get()->first();
        $prop = Propertylist::
                            where('item_type','like', '%' . $base->type . '%')->
                            orWhere('item_class','like', '%' . $base->class . '%')->
                            orWhere('item_name' ,'like', '%' . $base->name . '%' )->
                            inRandomOrder()->
                            limit(1)->
                            get()->first();

        $prop_body = $prop->type . ';';
        $prop_body .= $prop->value_type . ';';
        $prop_body .= $prop->name . ';';

        if($prop->value_type === 'range'){
            $prop_body .= $prop->min_value . '/';
            $prop_body .= random_int($prop->min_value, $prop->max_value ) . ';';
        }
        else{
            $prop_body .= random_int($prop->min_value, $prop->max_value ) . ';';
        }
        $armour = new Armour();
        $armour->name = $base->name;
        $armour->type =  $base->type;
        $armour->class = $base->class;

        if( $base->armour ) {$armour->armour = $base->armour;}
        if( $base->energy_regen ) {$armour->energy_regen = $base->energy_regen;}
        if( $base->add_spell_damage ) {$armour->add_spell_damage = $base->add_spell_damage;}
        $armour->img_path = $base->img_path;
        if($char_id){
            $armour->char_id = $char_id;
        }
        $armour->property_1 = $prop_body;
        $armour->slot_type = 'inv';
        $armour->slot = min($this->inv_service->getFreeSlots($char_id));
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
//        if($r < 33){
//            return $this->createRandomArmour($char_id);
//        }
//        else if($r < 66){
//            return $this->createRandomWeapon($char_id);
//        }
//        else{
//            return $this->createRandomUsed($char_id);
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

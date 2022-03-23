<?php

namespace App\Http\Services;

use App\Models\WeaponList;
use App\Models\Weapon;
use App\Models\WeaponPropertylist;

class ItemService{


    public function createRandomWeapon($char_id = false){

        $base = WeaponList::inRandomOrder()->limit(1)->get()->first();
        $prop = WeaponPropertylist::inRandomOrder()->limit(1)->get()->first();

        $prop_body = $prop->type . ';';
        $prop_body .= $prop->inc_type . ';';
        $prop_body .= $prop->name . ';';

        if($prop->inc_type === 'between'){
            $prop_body .= $prop->min_value . '/';
            $prop_body .= random_int($prop->min_value, $prop->max_value ) . ';';
        }
        else{
            $prop_body .= random_int($prop->min_value, $prop->max_value ) . ';';
        }

        $weapon = new Weapon();
        $weapon->name = $base->name;

        $weapon->type = 'equip';
        $weapon->subtype = 'weapon';
        $weapon->min_damage = $base->min_damage;
        $weapon->max_damage = $base->max_damage;
        $weapon->img_path = $base->img_path;
        $weapon->class = $base->class;
        $weapon->attack_speed = $base->attack_speed;
        $weapon->attack_range = $base->attack_range;
        if($char_id){
            $weapon->char_id = $char_id;
        }
        $weapon->property_1 = $prop_body;
        $weapon->save();


        return $weapon;
    }

}

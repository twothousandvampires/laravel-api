<?php

namespace App\Http\Services;
use App\Models\ArmourList;
use App\Models\WeaponList;
use App\Models\Weapon;
use App\Models\Armour;
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
        $weapon->type = 'weapon';
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

    public function createRandomArmour($char_id = false){
        $base = ArmourList::inRandomOrder()->limit(1)->get()->first();
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
        $armour->save();

        return $armour;
    }

}
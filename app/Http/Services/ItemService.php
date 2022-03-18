<?php

namespace App\Http\Services;
use App\Models\Node;
use App\Models\Weapon;
use App\Models\WeaponList;
use App\Models\WeaponPropertylist;
use Illuminate\Database\Eloquent\Model;

class ItemService{

    public function createRandomWeapon($char_id){
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


        return $prop;
    }

}

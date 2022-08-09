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
use App\Models\EquipPropertylist;
use App\Models\BookPropertylist;
use App\Models\Property;
use App\Http\Services\Skill\Active\FireBall;
use Illuminate\Database\Eloquent\Model;
use App\Http\Services\SkillService;

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
        $base = BaseList::inRandomOrder()->select('name','type','class','price','img_path','subclass')->first()->toArray();
        $item_data = array_merge($item_data, $base);

        $item = Item::create($item_data);
        $property = EquipPropertylist::where('item_name', $base['name'])->select($rarity,'stat','name')->get();

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
        $base = BaseList::inRandomOrder()->where('type','used')->select('name','type','class','price','img_path','subclass')->first()->toArray();
        $item_data = array_merge($item_data, $base);

        $item = Item::create($item_data);

        $property = BookPropertylist::where('item_name', $base['name'])->get();



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

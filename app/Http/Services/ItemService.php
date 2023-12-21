<?php

namespace App\Http\Services;

use App\Models\EquipDetail;
use App\Models\EquipDetailList;
use App\Models\GemDetail;
use App\Models\GemDetailList;
use App\Models\GemProperties;
use App\Models\GemPropertyList;
use App\Models\GemSkills;
use App\Models\ItemsList;
use App\Models\Item;
use App\Models\EquipPropertiesList;
use App\Models\NodeContent;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\GemSkillList;

class ItemService{

    const GEM_TYPE_ALL = 'all';

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
    public function createRandomScroll($char_id){
        $item_data = [];

        if($char_id){
            $item_data['char_id'] = $char_id;
        }

        $item_data['slot'] = $this->inv_service->getFreeSlots($char_id);

        //generate quality
        $quality = mt_rand(1,4);

        $item_data['quality'] = $quality;

        $base = ItemsList::where('type', Item::ITEM_TYPES_SCROLL)
                        ->inRandomOrder()
                        ->select('name','type','class','subclass','rarity')
                        ->first()
                        ->toArray();

        $item_data = array_merge($item_data, $base);

        $item = Item::create($item_data);

        return Item::find($item->id)->details();
    }
    public function createRandomEquip($char_id = false, $name = null){

        $item_data = [];
        if($char_id){
            $item_data['char_id'] = $char_id;
        }

        $item_data['slot'] = $this->inv_service->getFreeSlots($char_id);

        $quality = mt_rand(1,4);

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


        $property = EquipPropertiesList::where('item_name', $base['name'])->select(ITEM::QUALITY[$quality], 'stat' , 'name' , 'prop_type')->get();


        var_dump($property);die;
        foreach ($property as $prop){
            Property::create(['item_id' => $item->id,
                              'name' => $prop->name,
                              'value' => $prop[ITEM::QUALITY[$quality]],
                              'stat' => $prop->stat,
                              'prop_type' => $prop->prop_type]);
        }

        return Item::find($item->id)->details();
    }

    public function createRandomGem($char_id = false){
        $item_data = [];
        if($char_id){
            $item_data['char_id'] = $char_id;
        }

        $quality = mt_rand(1,4);

        $item_data['slot'] = $this->inv_service->getFreeSlots($char_id);

        $base = ItemsList::inRandomOrder()
                            ->where('type',Item::ITEM_TYPE_GEM)
                            ->select('id','name','type','rarity')
                            ->first()
                            ->toArray();

        $item = Item::create(array_merge($item_data, $base));

        $detail_base = GemDetailList::where('item_list_id',$base['id'])->first();

        $datails = GemDetail::create([
            'item_id' => $item->id,
            'gem_type' => $detail_base->gem_type,
            'gem_class' => $detail_base->gem_class,
            'gem_quality' => $quality]);

        $props = GemPropertyList::where('item_name', $item->name)
                                ->select(ITEM::QUALITY[$quality],'prop_name')
                                ->get();

        foreach ($props as $prop){
            GemProperties::create(['item_id' => $item->id,
                'name' => $prop->prop_name,
                'value' => $prop[ITEM::QUALITY[$quality]],
            ]);
        }

        $skill_query = GemSkillList::query();

        if($detail_base->gem_type !== Item::GEM_TYPE_ALL){
            $skill_query = $skill_query->where('gem_type', $detail_base->gem_type);
        }

        if($detail_base->gem_class !== Item::GEM_CLASS_ALL){
            $skill_query = $skill_query->where('gem_class', $detail_base->gem_class);
        }

        $skill = $skill_query->inRandomOrder()->first();

        GemSkills::create([
            'item_id' => $item->id,
            'name' => $skill->name,
            'skill_type' => $skill->gem_type,
            'skill_class' => $skill->gem_class,
            'exp_needed' => $skill->exp_needed,
            'max_level' => $skill->max_level,
            'level' => 1
        ]);


        return Item::find($item->id)->details();
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

    public function createByName($item_name, InventoryService $inventoryService, $char_id = false){


        $item_data = [];
        if($char_id){
            $item_data['char_id'] = $char_id;
        }

        $item_data['slot'] = $inventoryService->getFreeSlots($char_id);

        $quality = mt_rand(1,4);

        $item_data['quality'] = $quality;

        $base = ItemsList::where('name', $item_name)
                        ->select('id','name','type','rarity')
                        ->first()
                        ->toArray();

        $item_data = array_merge($item_data, $base);

        $item = Item::create($item_data);

        if($item->type == Item::ITEM_TYPE_EQUIP){

            $detail_base = EquipDetailList::where('item_list_id', $base['id'])->first();

            $datails = EquipDetail::create([
                'item_id' => $item->id,
                'equip_type' => $detail_base->equip_type,
                'equip_class' => $detail_base->equip_class,
                'equip_quality' => $quality]);


            $property = EquipPropertiesList::where('item_name', $base['name'])->select(ITEM::QUALITY[$quality],'stat','name','prop_type','sub_type','inc_type')->get();

            foreach ($property as $prop){
                Property::create(['item_id' => $item->id,
                    'name' => $prop->name,
                    'value' => $prop[ITEM::QUALITY[$quality]],
                    'stat' => $prop->stat,
                    'prop_type' => $prop->prop_type,
                    'sub_type' => $prop->sub_type,
                    'inc_type' => $prop->inc_type]);
            }

        }
        elseif($item->type == Item::ITEM_TYPE_GEM){

            $detail_base = GemDetailList::where('item_list_id',$base['id'])->first();

            $datails = GemDetail::create([
                'item_id' => $item->id,
                'gem_type' => $detail_base->gem_type,
                'gem_class' => $detail_base->gem_class,
                'gem_quality' => $quality]
            );


            $props = GemPropertyList::where('item_name', $item->name)
                ->select([ITEM::QUALITY[$quality], 'prop_name'])
                ->get();

            foreach ($props as $prop){
                GemProperties::create(['item_id' => $item->id,
                                'prop_name' => $prop->prop_name,
                                'value' => $prop[ITEM::QUALITY[$quality]],
                ]);
            }

            $skill_query = GemSkillList::query();

            if($detail_base->gem_type !== Item::GEM_TYPE_ALL){
                $skill_query = $skill_query->where('gem_type', $detail_base->gem_type);
            }

            if($detail_base->gem_class !== Item::GEM_CLASS_ALL){
                $skill_query = $skill_query->where('gem_class', $detail_base->gem_class);
            }

            $skill = $skill_query->inRandomOrder()->first();

            GemSkills::create([
                'item_id' => $item->id,
                'name' => $skill->name,
                'skill_type' => $skill->gem_type,
                'skill_class' => $skill->gem_class,
                'exp_needed' => $skill->exp_needed,
                'level' => 1
            ]);

        }

        return Item::find($item->id)->details();
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

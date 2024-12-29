<?php 
namespace App\Http\Actions\Craft;

use App\Models\Item;
use App\Models\EquipDetail;
use App\Models\Property;
use App\Models\EquipPropertiesList;
use App\Http\Services\ItemService;
use App\Http\Actions\Action;

class AddProperty extends Action
{
    public $requared_params = [
        'item_id',
        'used_id'
    ];

    public function do($request){

        $item = Item::find($request->item_id);
        $details = EquipDetail::where('item_id', $item->id)->first();

        $count = Property::where('item_id', $item->id)->count();

        if($count >= $details->max_property_count && $request->prop_type != 'all'){
            $this->setUnsuccess('maximum count of properties');
            return $this->answer;
        }

        $itemService = new ItemService();
        $used = Item::find($request->used_id);
        $itemService->useUsed($used);
        
    
        switch ($request->prop_type){
            case 'all':
                $prop = EquipPropertiesList::select('id', ITEM::QUALITY[$details->equip_quality], 'stat' , 'name', 'sub_type')
                ->inRandomOrder()
                ->first();
                Property::create([
                   'name' => $prop->name,
                   'item_id' => $request->item_id,
                   'stat' => $prop->stat,
                    'sub_type' => $prop->sub_type,
                    'value' => $prop[ITEM::QUALITY[$details->equip_quality]],
                    'prop_list_id' => $prop->id
                ]);
                break;
            case 'item':
                    $prop = EquipPropertiesList::where('type', $this->TypeIntToStr($details->equip_type))
                    ->where('class', $this->ClassIntToStr($details->equip_class))
                    ->where('item_name', 'like', '%crafted%')
                    ->whereNull('requared_slot')
                    ->select('id', ITEM::QUALITY[$details->equip_quality], 'stat' , 'name', 'sub_type')
                    ->inRandomOrder()
                    ->first();

                    Property::create([
                       'name' => $prop->name,
                       'item_id' => $request->item_id,
                       'stat' => $prop->stat,
                        'sub_type' => $prop->sub_type,
                        'value' => $prop[ITEM::QUALITY[$details->equip_quality]],
                        'prop_list_id' => $prop->id
                    ]);
                    break;
            case 'class':
                    $prop = EquipPropertiesList::where('class', $this->ClassIntToStr($details->equip_class))
                    ->where('item_name', 'like', '%crafted%')
                    ->whereNull('requared_slot')
                    ->select('id', ITEM::QUALITY[$details->equip_quality], 'stat' , 'name', 'sub_type')->inRandomOrder()->first();
                    Property::create([
                        'name' => $prop->name,
                        'item_id' => $request->item_id,
                        'stat' => $prop->stat,
                        'sub_type' => $prop->sub_type,
                        'value' => $prop[ITEM::QUALITY[$details->equip_quality]],
                        'prop_list_id' => $prop->id
                    ]);
                    break;
            case 'type':
                $prop = EquipPropertiesList::where('type', $this->TypeIntToStr($details->equip_type))
                ->where('item_name', 'like', '%crafted%')
                ->select('id', ITEM::QUALITY[$details->equip_quality], 'stat' , 'name', 'sub_type')
                ->inRandomOrder()
                ->first();

                Property::create([
                    'name' => $prop->name,
                    'item_id' => $request->item_id,
                    'stat' => $prop->stat,
                    'sub_type' => $prop->sub_type,
                    'value' => $prop[ITEM::QUALITY[$details->equip_quality]],
                    'prop_list_id' => $prop->id
                ]);
                break;
        }
               
        $this->addData(['items' => Item::where('char_id', $request->char_id)->get()]);
        return $this->answer;
    }

    private function TypeIntToStr($type){
        if($type == 1){
            return 'weapon';
        }
        else if($type == 2){
            return 'armour';
        }
        else if($type == 3){
            return 'accessory';
        }
    }
    private function ClassIntToStr($class){
        if($class == 1){
            return 'combat';
        }
        else if($class == 2){
            return 'sorcery';
        }
        else if($class == 3){
            return 'movement';
        }
    }
}
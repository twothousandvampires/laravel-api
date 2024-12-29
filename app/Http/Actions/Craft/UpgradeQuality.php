<?php 
namespace App\Http\Actions\Craft;

use App\Models\Item;
use App\Models\EquipDetail;
use App\Models\Property;
use App\Http\Services\ItemService;
use App\Http\Actions\Action;
use App\Models\EquipPropertiesList;


class UpgradeQuality extends Action
{
    public $requared_params = [
        'item_id',
        'used_id'
    ];

    public function do($request){

        $item = Item::find($request->item_id);

        $prev_q = $item->details->equip_quality;
        $new_q = $prev_q + 1;

        if($new_q > 4){
            $new_q = 4;
        }

        EquipDetail::where('item_id', $request->item_id)->update([
            'equip_quality' => $new_q
        ]);

        $props = Property::where('item_id', $request->item_id)->get();

        foreach ($props as $prop){
            $value = EquipPropertiesList::where('id', $prop->prop_list_id)->select(ITEM::QUALITY[$new_q])->first();
            $prop->value = $value[ITEM::QUALITY[$new_q]];
            $prop->save();
        }

        $itemService = new ItemService();
        $used = Item::find($request->used_id);
        $itemService->useUsed($used);
        
        $item->fresh();

        $this->addData(['items' => Item::where('char_id', $request->char_id)->get()]);

        return $this->answer;
    }
}
<?php 
namespace App\Http\Actions\Craft;

use App\Models\Item;
use App\Models\EquipDetail;
use App\Http\Actions\Action;
use App\Http\Services\ItemService;

class UpgradeEffect extends Action
{
    public $requared_params = [
        'item_id',
        'used_id'
    ];

    public function do($request){

        $item = Item::find($request->item_id);

        $details = EquipDetail::where('item_id', $item->id)->first();
        $effect = mt_rand(5, 10);
        $details->inc_effect += $effect;
        $details->save();

        $used = Item::find($request->used_id);
        $itemService = new ItemService();
        $itemService->useUsed($used);

        $item->fresh();

        $this->addData(['items' => Item::where('char_id', $request->char_id)->get()]);

        return $this->answer;
    }
}
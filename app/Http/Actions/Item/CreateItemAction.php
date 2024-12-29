<?php 
namespace App\Http\Actions\Item;

use App\Models\Character;
use App\Models\Node;
use App\Http\Services\ItemService;
use App\Http\Actions\Action;

class CreateItemAction extends Action
{
    public $requared_params = [
        'item_name'
    ];

    public function do($request){

        $itemService = new ItemService();
        $item = $itemService->createByName($request->item_name, $request->char_id);
        $this->addData(['item' => $item]);

        return $this->answer;
    }
}
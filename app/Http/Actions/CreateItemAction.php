<?php 
namespace App\Http\Actions;

use App\Models\Character;
use App\Models\Node;
use App\Http\Services\ItemService;

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
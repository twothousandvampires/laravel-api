<?php 
namespace App\Http\Actions\Item;

use App\Models\Item;

use App\Http\Actions\Action;

class DeleteItem extends Action
{

    public $requared_params = [
        'item_id'
    ];

    public function do($request){

        $item = Item::find($request->item_id);

        if($item){
            $item->delete();
        }
        else{
            $this->setUnsuccess('item not found');
        }
        
        return $this->answer;
    }
}
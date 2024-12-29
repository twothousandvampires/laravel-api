<?php 
namespace App\Http\Actions\Item;

use App\Models\Character;
use App\Models\Item;
use App\Http\Services\itemService;
use App\Http\Actions\Action;

class UseItem extends Action
{

    public $requared_params = [
        'ids_list'
    ];

    public function do($request){

        $character = Character::find($request->char_id);

        if($character){
            $itemService = new ItemService();
            foreach ($request->ids_list as $id){
                $item = Item::where('id', $id)->first();
                $itemService->useUsed($item, $character);
            }
        }
        else{
            $this->setUnsuccess('character not found');
        }
      
        $this->addData(['char' => $character]);
        
        return $this->answer;
    }
}
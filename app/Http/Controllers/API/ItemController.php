<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Services\CharacterService;
use App\Http\Services\ItemService;
use App\Http\Services\NodeService;
use App\Models\Character;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends BaseController
{
    public $node_service;
    public $item_service;
    private $character_service;

    private function isOwner($char_id){
        $character = Character::find($char_id);
        if($character->user_id === Auth::user()->id){
            return $character;
        }
        return false;
    }

    function __construct()
    {
        $this->node_service = new NodeService();
        $this->item_service = new ItemService();
        $this->character_service = new CharacterService();
    }


    public function change(Request $request){

        $from = Item::find($request->from);
        $character = $this->isOwner($from->char_id);


        if($character){

            if($request->exchange){
                $to = Item::find($request->to);
                $temp_slot = $from->slot;

                $from->slot = $to->slot;
                $from->save();

                $to->slot = $temp_slot;
                $to->save();
            }
            else{
                $from->slot = $request->to;
                $from->save();
            }
            return $this->sendResponse([], 'Successfully.');
        }
    }




    public function create(Request $request){

        $item = $this->item_service->createRandomItem($request->char_id);
        return $this->sendResponse(['item' => $item], 'Successfully.');

    }

    public function delete(Request $request){

        $item = Item::find($request->id);
        $item->delete();

        return $this->sendResponse([], 'Successfully.');
    }

    public function use(Request $request, $item_id){

        $item = Item::with('properties')->find($item_id);
        $character = Character::find($item->char_id);

        if($character->user_id === Auth::user()->id){

            $skill = $this->item_service->use($request, $item, $character);
            $type = $item->class;
            $item->delete();
            switch ($type){
                case 'book':
                    return $this->sendResponse(['data'=>$skill], 'Successfully.',);
            }
        }
    }

}

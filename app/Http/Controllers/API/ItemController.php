<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Services\CharacterService;
use App\Http\Services\ItemService;
use App\Http\Services\NodeService;
use App\Models\Armour;
use App\Models\Character;
use Illuminate\Http\Request;
use App\Models\Weapon;
use App\Models\Used;
use Illuminate\Support\Facades\Auth;

class ItemController extends BaseController
{
    public $node_service;
    public $item_service;
    private $character_service;

    function __construct()
    {
        $this->node_service = new NodeService();
        $this->item_service = new ItemService();
        $this->character_service = new CharacterService();
    }


    public function change(Request $request){

        $changed_item = json_decode($request->changed_item);
        $exchanged_item = json_decode($request->exchanged_item);



        $change = $exchanged_item->exchange;

        switch ($changed_item->type){
            case 'weapon':
                $which = Weapon::find($changed_item->id);
                break;
        }


        if($change){

            switch ($exchanged_item->type){
                case 'weapon':
                    $for_what = Weapon::find($exchanged_item->id);
                    break;
            }

            $temp_slot = $which->slot;
            $temp_slot_type = $which->slot_type;

            $which->slot = $for_what->slot;
            $which->slot_type = $for_what->slot_type;
            $which->save();

            $for_what->slot = $temp_slot;
            $for_what->slot_type = $temp_slot_type;
            $for_what->save();
        }
        else{
            $which->slot = $exchanged_item->id;
            $which->slot_type = $exchanged_item->type;
            $which->save();
        }

        return $this->sendResponse(['which' => $which,'for_what' => $for_what ?? null], 'Successfully.');
    }

    public function create(Request $request){

        $item = $this->item_service->createRandomItem($request->char_id);
        return $this->sendResponse(['item' => $item], 'Successfully.');

    }

    public function delete(Request $request){

        switch ($request->type){
            case 'weapon':
                $item = Weapon::find($request->id);
                $item->delete();
                break;
            case 'armour':
                $item = Armour::find($request->id);
                $item->delete();
                break;
            case 'used':
                $item = Used::find($request->id);
                $item->delete();
                break;
        }

        return $this->sendResponse([], 'Successfully.');
    }

    public function use(Request $request, $item_id){



        $item = Used::find($item_id);


        $character = Character::find($item->char_id);



        if($character->user_id === Auth::user()->id){


            $skill = $this->item_service->use($item, $character);

            $type = $item->class;
            $item->delete();

            switch ($type){
                case 'book':
                    return $this->sendResponse(['data'=>$skill,'type'=>$type], 'Successfully.',);
            }
        }
    }

}

<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Services\CharacterService;
use App\Http\Services\ItemService;
use App\Http\Services\NodeService;
use App\Models\Armour;
use Illuminate\Http\Request;
use App\Models\Weapon;
use App\Models\Used;

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


        switch ($request->which_type){
            case  'weapon':
                $which = Weapon::find($request->which_id);
                break;
        }

        if($request->for_what_id){
            switch ($request->for_what_type){
                case  'weapon':
                    $for_what = Weapon::find($request->for_what_id);
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
            $which->slot = $request->slot;
            $which->slot_type = $request->slot_type;
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

}

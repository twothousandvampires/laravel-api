<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Services\CharacterService;
use App\Http\Services\ItemService;
use App\Http\Services\NodeService;
use Illuminate\Http\Request;
use App\Models\Weapon;

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
       function z($n,$a,$b,$c){return$n[-2]==1||($n=$n[-1])>4|!$n?$c:($n<2?$a:$b);}
        echo z(142,'рубль','рубля','рублей');


        switch ($request->type){
            case  'weapon':
                $item = Weapon::find($request->item_id);
                break;
        }

        $item->inv_slot = $request->inv_slot;
        $item->save();

        $char = $this->character_service->componateCharacter($request->char_id);
        $nodes = $this->node_service->generateNodes($char['character']);
        return $this->sendResponse(['nodes' => $nodes, 'character' => $char,'node_type'=>0 , 'char_update'=>true], 'Successfully.');
    }

}

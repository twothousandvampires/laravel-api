<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Services\CharacterService;
use App\Http\Services\ItemService;
use App\Http\Services\NodeService;
use App\Models\SkillTreeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Character;
use App\Models\Node;
use App\Models\SkillTree;

class CharacterController extends BaseController
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

    private function isOwner($char_id){
        $character = Character::find($char_id);
        if($character->user_id === Auth::user()->id){
            return $character;
        }
        return false;
    }

    public function create(Request $request){
        $char = new Character();
        $char->name = $request->name;
        $char->user_id = Auth::user()->id;
        $char->x = 0;
        $char->y = 0;
        $char->save();
        SkillTreeModel::make($char->id);
        $this->node_service->generateSingleNode(0,0,4,$char->id);

        return $this->sendResponse($char, 'Successfully.');
    }

    public function world($char_id){
        $character = $this->isOwner($char_id);
        if($character){
            $char = $this->character_service->componateCharacter($char_id);
            $nodes = $this->node_service->generateNodes($char['character']);
            return $this->sendResponse(['nodes' => $nodes, 'character' => $char,'node_type'=> 0 ]);
        }
        return $this->sendError('something went wrong.');
    }

    public function delete($char_id){
            $character = $this->isOwner($char_id);
            if($character){
                $character->delete();
                return $this->sendResponse(true,'Successfully.');
            }
            return $this->sendError('Character not find.');
    }

    public function move(Request $request, $char_id){
        $character = $this->isOwner($char_id);
        if($character){
            $new_node = Node::getNodeByCoord($request->x,$request->y,$char_id);
            switch ($new_node->type){
                case 1:
                    $character->x = $request->x;
                    $character->y = $request->y;
                    $character->save();
                    return $this->sendResponse(['node'=>$new_node,'node_type'=>1]);
                case 4:
                    $character->x = $request->x;
                    $character->y = $request->y;
                    $character->save();
                    $nodes = $this->node_service->generateNodes($character);
                    return $this->sendResponse(['nodes'=>$nodes,'char'=>$character,'node_type'=>4]);
                default :
                    $character->x = $request->x;
                    $character->y = $request->y;
                    $character->save();
                    $new_node->visited = 1;
                    $new_node->save();
                    $nodes = $this->node_service->generateNodes($character);
                    return $this->sendResponse(['nodes'=>$nodes,'char'=>$character,'node_type'=>0]);
            }
        }
    }

    public function win(Request $request){
        if($this->isOwner($request->user_id)){
            try{
                $char = Character::find($request->char_id);
                $node = Node::getNodeByCoord($char->x,$char->y,$char->id);
                $node->type = 0;
                $node->content_img = null;
                $node->save();
                $nodes = $this->node_service->generateNodes($char);
                return $this->sendResponse(['nodes'=>$nodes,'char'=>$char,'node_type'=>0], 'Successfully.');
            }
            catch (\Exception $e){
                return $e;
            }
        }
    }
}

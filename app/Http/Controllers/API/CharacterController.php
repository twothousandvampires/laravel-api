<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Services\CharacterService;
use App\Http\Services\ItemService;
use App\Http\Services\NodeService;
use App\Models\Item;
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

    public function useTorch($char_id){
        $character = $this->isOwner($char_id);
        if($character){
            $this->node_service->torch($character);
        }
    }

    public function get($char_id){
        $char = $this->character_service->componateCharacter($char_id);
        return $this->sendResponse($char, 'Successfully.');
    }

    public function create(Request $request){
        $char = new Character();
        $char->name = $request->name;
        $char->user_id = Auth::user()->id;
        $char->x = 0;
        $char->y = 0;
        $char->save();
        $this->node_service->generateSingleNode(0,0,4,$char->id);
        return $this->sendResponse($char, 'Successfully.');
    }

    public function world($char_id){
        $character = $this->isOwner($char_id);
        if($character){
            $char = $this->character_service->componateCharacter($char_id);
            $nodes = $this->node_service->generateNodes($char);
            return $this->sendResponse($nodes);
        }
        return $this->sendError('something went wrong.');
    }

    public function delete($char_id){
            $character = $this->isOwner($char_id);
            if($character){
                $character->delete();
                return $this->sendResponse(true,'Successfully.');
            }
            return $this->sendError('Character not found.');
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
                case 2:
                    $character->x = $request->x;
                    $character->y = $request->y;
                    $character->save();
                    $item = $this->item_service->createRandomItem($character->id);
                    if(!$item->slot){
                        Item::find($item->id)->delete();
                    }
                    $new_node->content_type = null;
                    $new_node->type = 0;
                    $new_node->save();
                    $nodes = $this->node_service->generateNodes($character);
                    return $this->sendResponse(['nodes'=>$nodes,'char'=>$character,'node_type'=>2, 'item' =>$item]);
                default :
                    $character->x = $request->x;
                    $character->y = $request->y;
                    $character->save();
                    $new_node->save();
                    $nodes = $this->node_service->generateNodes($character);
                    return $this->sendResponse(['nodes'=>$nodes,'node_type'=>0]);
            }
        }
    }

    public function win(Request $request){
        $character = $this->isOwner($request->char_id);
        if($character){
            try{
                $node = Node::getNodeByCoord($character->x,$character->y,$character->id);
                $node->type = 0;
                $character->addExp($node);
                $character->save();
                $node->content_type = null;
                $node->save();
                $nodes = $this->node_service->generateNodes($character);
                return $this->sendResponse(['nodes'=>$nodes,'char'=>$character,'node_type'=>0], 'Successfully.');
            }
            catch (\Exception $e){
                return $e;
            }
        }
    }
}

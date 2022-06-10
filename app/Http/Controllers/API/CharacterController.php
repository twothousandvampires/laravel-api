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

    private function isOwner($user_id){
        return  true;
        return Auth::user()->id == $user_id;
    }

    public function create(Request $request){
        if($this->isOwner($request->user_id)){
            try{
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
            catch(\Exception $e){
                return $e;
            }
        }
        else{
            return $this->sendResponse('her', 'Successfully.');
        }

    }

    public function world(Request $request){
        if($this->isOwner($request->user_id)){
            try{
                $char = $this->character_service->componateCharacter($request->char_id);
                $nodes = $this->node_service->generateNodes($char['character']);
                return $this->sendResponse(['nodes' => $nodes, 'character' => $char,'node_type'=>0 , 'char_update'=>true], 'Successfully.');
            }
            catch (\Exception $e){
                return $e;
            }


        }
    }

    public function delete(Request $request){

            $char = Character::find($request->char_id);
            if($char->user_id == Auth::user()->id){
                $char->delete();
                return $this->sendResponse(true,'Successfully.');
            }
            else{
                return $this->sendError('Character not find.');
            }

    }

    public function move(Request $request){
        if($this->isOwner($request->user_id)){
            try{
                $new_node = Node::getNodeByCoord($request->x,$request->y,$request->char_id);
                $char = Character::find($request->char_id);
                switch ($new_node->type){
                    case 1:
                        $char->x = $request->x;
                        $char->y = $request->y;
                        $char->save();
                        $distance = round(sqrt(pow($char->x,2) + pow($char->y,2)),2);
                        return $this->sendResponse(['char'=>$char, 'dist'=>$distance,'number'=>random_int(1,1),'node_type'=>1], 'Successfully.');
                    case 4:
                        $char->x = $request->x;
                        $char->y = $request->y;
                        $char->save();
                        $nodes = $this->node_service->generateNodes($char);
                        return $this->sendResponse(['nodes'=>$nodes,'char'=>$char,'node_type'=>4], 'Successfully.');
                    default :
                        $char->x = $request->x;
                        $char->y = $request->y;
                        $char->save();
                        $new_node->visited = 1;
                        $new_node->save();
                        $nodes = $this->node_service->generateNodes($char);
                        return $this->sendResponse(['nodes'=>$nodes,'char'=>$char,'node_type'=>0], 'Successfully.');
                }

            }
            catch (\Exception $e){
                return $e;
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

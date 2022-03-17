<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Services\NodeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Character;
use App\Models\Node;

class CharacterController extends BaseController
{

    public $node_service;

    private function isOwner($user_id){
        return Auth::user()->id == $user_id;
    }

    function __construct(){
        $this->node_service = new NodeService();
    }
    public function create(Request $request){
        if($this->isOwner($request->user_id)){
            try{
                $char = new Character();
                $char->name = $request->name;
                $char->class = $request->class_name;
                $char->user_id = $request->user_id;
                $char->x = 0;
                $char->y = 0;
                $char->save();
                $this->node_service->generateSingleNode(0,0,4,$char->id);
                return $this->sendResponse($char, 'Successfully.');
            }
            catch(\Exception $e){

            }
        }
        else{
            return $this->sendResponse('her', 'Successfully.');
        }
    }
    public function world(Request $request){
        if($this->isOwner($request->user_id)){
            $char = Character::find($request->char_id);
            $nodes = $this->node_service->generateNodes($char);
            return $this->sendResponse(['nodes' => $nodes, 'char' => $char,'node_type'=>0 , 'char_update'=>true], 'Successfully.');
        }
    }
    public function delete(Request $request){
        if($this->isOwner($request->user_id)){
            $char = Character::find($request->char_id);
            if($char){
                $char->delete();
                return $this->sendResponse(true,'Successfully.');
            }
            else{
                return $this->sendError('Character not find.');
            }
        }
    }
    public function move(Request $request){
        if($this->isOwner($request->user_id)){
            $new_node = Node::getNodeByCoord($request->x,$request->y,$request->char_id);
            $char = Character::find($request->char_id);
            switch ($new_node->type){
                case 0:
                    $char->x = $request->x;
                    $char->y = $request->y;
                    $char->save();
                    $new_node->visited = 1;
                    $new_node->save();
                    $nodes = $this->node_service->generateNodes($char);
                    return $this->sendResponse(['nodes'=>$nodes,'char'=>$char,'node_type'=>0], 'Successfully.');
                case 1:
                    $char->x = $request->x;
                    $char->y = $request->y;
                    $char->save();
                    $distance = round(sqrt(pow($char->x,2) + pow($char->y,2)),2);
                    return $this->sendResponse(['char'=>$char, 'dist'=>$distance,'number'=>random_int(1,1),'node_type'=>1], 'Successfully.');
            }
        }
    }
}

<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Services\NodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Character;
use App\Models\Node;

class CharacterController extends BaseController
{

    public $node_service;


    function __construct(){

        $this->node_service = new NodeService();
    }
    public function create($id, Request $request){

        if(Auth::user()->id == $id){
            try{

                $char = new Character();
                $char->name = $request->name;
                $char->class = $request->class_name;
                $char->user_id = $id;
                $char->x = 0;
                $char->y = 0;
                $char->save();

                $this->node_service->generateSingleNode(0,0,4,$char->id);

                return $this->sendResponse($char, 'Product retrieved successfully.');

            }
            catch(\Exception $e){

            }
        }
        else{
            return $this->sendResponse('her', 'Product retrieved successfully.');
        }
    }
    public function world($user_id,$char_id){
        if(Auth::user()->id == $user_id){
            $char = Character::find($char_id);
            $nodes = $this->node_service->generateNodes($char);
            return $this->sendResponse([$nodes,$char], 'Product retrieved successfully.');
        }
    }

    public function move($user_id,$char_id,Request $request){
        if(Auth::user()->id == $user_id){

            $new_node = Node::getNodeByCoord($request->x,$request->y,$char_id);

            $char = Character::find($char_id);

            switch ($new_node->type){
                case 0:
                    $char->x = $request->x;
                    $char->y = $request->y;
                    $char->save();
                    $nodes = $this->node_service->generateNodes($char);
                    return $this->sendResponse([$nodes,$char,0], 'Product retrieved successfully.');
                    break;
                case 1:
                    return $this->sendResponse([random_int(5,10),random_int(5,10),1], 'Product retrieved successfully.');
                    break;
            }

        }
    }
}

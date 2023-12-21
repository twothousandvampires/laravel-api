<?php
namespace App\Http\Controllers\API;

use App\Http\Services\CharacterService;
use App\Http\Services\ItemService;
use App\Http\Services\NodeContentService;
use App\Http\Services\NodeService;
use App\Models\Item;
use App\Models\Character;
use App\Models\Node;
use Illuminate\Http\Request;

class CharacterController extends BaseController
{

    //to do
//    public function useTorch($char_id){
//        $this->node_service->torch($character);
//    }

    public function get($char_id){
        $character = Character::find($char_id)->getItems();
        return $this->sendResponse($character, 'Successfully.');
    }

    public function create(Request $request, CharacterService $characterService){
        $char = $characterService->createCharacter($request, new NodeService());
        return $this->sendResponse($char, 'Successfully.');
    }

    public function world($char_id, NodeService $nodeService){

        $character = Character::find($char_id)->items();
        $nodes = $nodeService->generateNodes($character);
        if($character && $nodes){
            return $this->sendResponse($nodes, 'Successfully.');
        }

        return $this->sendError('something went wrong.');
    }

    public function delete($char_id){

        $character = Character::find($char_id)->items();
        if( $character->delete()){
            return $this->sendResponse(true,'Successfully.');
        }

        return $this->sendError('Character not found.');
    }

    public function set($id, Request $request){

        $character = Character::find($id);
        if($character){
            $character->life = $request->life;
            $character->mana = $request->mana;
            $character->dead = $request->dead;
            $character->save();
        }
    }

    public function move(Request $request, $char_id, NodeService $nodeService, ItemService $itemService){

        $character = Character::find($char_id);
        $new_node = Node::getNodeByCoord($request->x,$request->y,$char_id);

        switch ($new_node->type) {
            case 1:
                $character->update([
                    'x' =>  $request->x,
                    'y' =>  $request->y,
                ]);
                return $this->sendResponse(['node' => $new_node, 'node_type' => 1]);
            case 4:
                $character->x = $request->x;
                $character->y = $request->y;
                $character->save();
                $nodes = $nodeService->generateNodes($character);
                return $this->sendResponse(['nodes' => $nodes, 'char' => $character, 'node_type' => 4]);
            case 2:
                $character->x = $request->x;
                $character->y = $request->y;
                $character->save();
                $node_content = $new_node->content()->first();
                $item = $itemService->createItemFromTreasure($char_id, $node_content->content_type);
                if (!$item->slot) {
                    Item::find($item->id)->delete();
                }
                $new_node->type = 0;
                $new_node->save();
                $nodes = $nodeService->generateNodes($character);
                return $this->sendResponse(['nodes' => $nodes,
                    'char' => $character, 'node_type' => 2,
                    'item' => $item->slot ? $item : false]);
            default :
                $character->update([
                    'x' =>  $request->x,
                    'y' =>  $request->y,
                ]);
                $nodes = $nodeService->generateNodes($character);
                return $this->sendResponse(['nodes' => $nodes,'node_type' => 0]);
//            }

        }
    }

    public function win($char_id, NodeContentService $nodeContentService, NodeService $node_service){
        $character = Character::find($char_id);
        try {
            $node = Node::getNodeByCoord($character->x, $character->y, $character->id);
            $node->type = 0;
            $node->save();
            $character->addExp($nodeContentService->calcExp($node));
            $node_content = $node->content()->first();
            $node_content->content_type = 0;
            $node_content->save();

            $nodes = $node_service->generateNodes($character);
            return $this->sendResponse(['nodes'=>$nodes,'char'=>$character->items(),'node_type'=>0], 'Successfully.');
        }
        catch (\Exception $e){
            return $e;
        }

    }
}

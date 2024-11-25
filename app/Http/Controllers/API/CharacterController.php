<?php
namespace App\Http\Controllers\API;

use App\Http\Services\CharacterService;
use App\Http\Services\InventoryService;
use App\Http\Services\PassiveService;
use App\Models\NodeContent;
use App\Models\Passives;
use App\Models\PassivesList;
use App\Models\UsedDetail;
use Illuminate\Support\Facades\App;
use App\Http\Services\ItemService;
use App\Http\Services\NodeContentService;
use App\Http\Services\NodeService;
use App\Http\Services\Log;
use App\Models\Item;
use App\Models\Character;
use App\Models\Node;
use Illuminate\Http\Request;

class CharacterController extends BaseController
{
    public function setStarted($char_id){
        Character::where('id', $char_id)->update([
           'started' => 1
        ]);
    }
    public function useTorch($char_id): \Illuminate\Http\JsonResponse
    {
        $character = Character::find($char_id);
        if($character){
            if($character->torch > 0){
                Node::where('char_id', $character->id)->where('x', $character->x)->where('y', $character->y)->update([
                    'visited' => 1
                ]);
                $character->torch--;
                $character->save();
                return $this->sendResponse(true);
            }
            else{
                return $this->sendError('have no torches');
            }
        }
        return $this->sendError('char not found');
    }

    public function upgradePassive($char_id, $passive_id, PassiveService $passiveService): \Illuminate\Http\JsonResponse
    {
        $character = Character::find($char_id);
        $passive = Passives::find($passive_id);

        $cost = $passive->level * $passive->exp_cost;

        if($character->exp < $cost){
            return $this->sendError('not enough exp!(need ' . $cost . ')');
        }

        $passive->level ++;
        $passive->save();

        $character->exp -= $cost;
        $character->save();

        $passiveService->affect($passive, $character);

        return $this->sendResponse($character);
    }
    public function learnPassive($char_id, $passive_id, PassiveService $passiveService){
        $passive = Passives::find($passive_id);
        if($passive){
            $passive->level = 1;
            $passive->save();
            $character = Character::find($char_id);
            $passiveService->affect($passive, $character);

            $character->power += 10;
            $character->save();

            Passives::where('char_id', $char_id)->where('level', 0)->delete();
            return $this->sendResponse($character);
        }
    }
    public function getPassives($char_id): \Illuminate\Http\JsonResponse
    {

        $passives = Passives::where('char_id', $char_id)->pluck('name')->toArray();
        $character = Character::find($char_id);
        $cost = count($passives) * 100 + 100;

        if($character->exp < $cost){
            return $this->sendError('not enough exp!(need ' . $cost . ')');
        }

        $character->exp -= $cost;
        $character->save();

        $new = PassivesList::inRandomOrder()->whereNotIn('name', $passives)->limit(3)->get();

        foreach ($new as $item){
            Passives::create([
                'char_id' => $char_id,
                'stat' => $item->stat,
                'name' => $item->name,
                'add_per_level' => $item->add_per_level,
                'exp_cost' => $item->exp_cost,
                'description' => $item->description,
            ]);
        }

        return $this->sendResponse(Passives::where('char_id', $char_id)->where('level', 0)->get());
    }
    public function useItems($char_id, Request $request): \Illuminate\Http\JsonResponse
    {
        $character = Character::find($char_id);
        if($character){
            foreach ($request->data as $data){
                $item = Item::where('id', $data)->first();
                $item->delete();
            }
            return $this->sendResponse($request->data);
        }
       return $this->sendError('character not found');
    }

    public function get($char_id){
        $character = Character::find($char_id);
        return $this->sendResponse($character);
    }

    public function create(Request $request, CharacterService $characterService){
        $char = $characterService->createCharacter($request, new NodeService());
        return $this->sendResponse($char);
    }

    public function world($char_id, NodeService $nodeService){

        $character = Character::find($char_id);
        $nodes = $nodeService->generateNodes($character);
        if($character && $nodes){
            return $this->sendResponse(['nodes' => $nodes]);
        }

        return $this->sendError('something went wrong.');
    }

    public function delete($char_id){

        $character = Character::find($char_id);
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

    public function move(Request $request, $char_id, NodeService $nodeService, ItemService $itemService, InventoryService $inventoryService): \Illuminate\Http\JsonResponse
    {
        $character = Character::find($char_id);
        $new_node = Node::getNodeByCoord($request->x,$request->y,$char_id);

        $character->x = $request->x;
        $character->y = $request->y;

        if($new_node->travelled){
            $r = mt_rand(0,100);
            if($r <= 20){
                $character->getFood();
            }
        }
        else{
            $character->getFood();
            $new_node->travelled = 1;
        }
        $character->save();
        $log = App::make(Log::class);

        if($character->dead == 1){
            $log->addToLog('you have died');
            $nodes = $nodeService->generateNodes($character);
            return $this->sendResponse(['nodes' => $nodes, 'character' => $character, 'log' => $log->log]);
        }

        switch ($new_node->type) {
            case 0:
                $new_node->save();
                $nodes = $nodeService->generateNodes($character);
                return $this->sendResponse(['nodes' => $nodes, 'character' => $character, 'log' => $log->log]);
            case 1:
                $new_node->save();
                return $this->sendResponse(['node' => $new_node, 'fight' => 1]);
            case 2:
                $log = App::make(Log::class);

                $node_content = $new_node->content;
                $slot = $inventoryService->getFreeSlots($char_id);

                if ($slot) {
                    $item = $itemService->createByName(json_decode($node_content->content)->item, $character->id, $slot);
                    if($item){
                        $log->addToLog('your found the ' . $item->name);
                    }
                } else {
                    $log->addToLog('you found an item but you don`t have slots for it');
                }

                $character->fresh();

                $new_node->type = 0;
                $new_node->save();
                $new_node->content->delete();
                $nodes = $nodeService->generateNodes($character);

                return $this->sendResponse(['nodes' => $nodes, 'character' => $character, 'log' => $log->log]);
            case 3:
                $content = json_decode($new_node->content->content);
                if($content->enemy->total_count != 0){
                    return $this->sendResponse(['node' => $new_node, 'fight' => 1]);
                }
                else{
                    $nodeService->takeObject($character, $log, $new_node, new InventoryService(), new ItemService());
                    $character->fresh();

                    $new_node->type = 0;
                    $new_node->save();
                    $new_node->content->delete();
                    $nodes = $nodeService->generateNodes($character);
                    return $this->sendResponse(['nodes' => $nodes, 'character' => $character, 'log' => $log->log]);
                }
        }

        return $this->sendError('node not found');
    }

    public function win($char_id, NodeService $nodeService, ItemService $itemService): \Illuminate\Http\JsonResponse|\Exception
    {
        $character = Character::find($char_id);
        try {
            $log = App::make(Log::class);

            $node = Node::getNodeByCoord($character->x, $character->y, $character->id);

            if($node->type === Node::TYPE_OBJECT){
                $nodeService->takeObject($character, $log, $node, new InventoryService(), new ItemService());
            }
            else{
                $item = json_decode($node->content->content)->enemy->item;
                if($item){
                    $item = $itemService->createByName($item, $character->id);
                    if (!$item->slot) {
                        Item::find($item->id)->delete();
                        $log->addToLog('you found an item but you don`t have room for it');
                    }
                    else{
                        $character = $character->fresh();
                        $log->addToLog('your found the ' . $item->name);
                    }
                }
            }

            $node->type = 0;
            $node->save();
            $character->addExp($node->content);

            $node->content->delete();

            $nodes = $nodeService->generateNodes($character);
            return $this->sendResponse(['nodes' => $nodes,'char' => $character, 'log' => $log->log]);
        }
        catch (\Exception $e){
            return $e;
        }
    }
}

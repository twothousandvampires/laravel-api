<?php 
namespace App\Http\Actions\Character;

use App\Models\Character;
use App\Models\Node;
use App\Http\Services\NodeService;
use Illuminate\Support\Facades\App;
use App\Http\Services\Log;
use App\Http\Services\InventoryService;
use App\Http\Services\ItemService;
use App\Http\Actions\Action;

class MoveAction extends Action
{
    public function do($request){

        $character = Character::find($request->char_id);
        $nodeService  = new NodeService();

        if(!isset($request->x) || !isset($request->y)){
            $nodes = $nodeService->generateNodes($character);
            $this->addData(['char' => $character, 'nodes' => $nodes]);
            return $this->answer;
        }

        $new_node = Node::getNodeByCoord($request->x, $request->y, $request->char_id);

        $character->prev_x = $character->x;
        $character->prev_y = $character->y;

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
            $this->addData(['nodes' => $nodes, 'character' => $character, 'log' => $log->log]);
            return $this->answer;
        }

        switch ($new_node->type) {
            case 0:
                $new_node->save();
                $nodes = $nodeService->generateNodes($character);
                $this->addData(['nodes' => $nodes, 'character' => $character, 'log' => $log->log]);
                return $this->answer;

            case 1:
                $new_node->save();
                $this->addData(['node' => $new_node, 'fight' => 1, 'character' => $character]);
                return $this->answer;

            case 2:
                $log = App::make(Log::class);
                $inventoryService = new InventoryService();
                $itemService = new ItemService();

                $node_content = $new_node->content;
                $slot = $inventoryService->getFreeSlots($character->id);

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

                $this->addData(['nodes' => $nodes, 'character' => $character, 'log' => $log->log]);
                return $this->answer;

            case 3:
                $content = json_decode($new_node->content->content);
                if($content->enemy->total_count != 0){
                    $this->addData(['node' => $new_node, 'fight' => 1]);
                    return $this->answer;
                }
                else{
                    $nodeService->takeObject($character, $log, $new_node, new InventoryService(), new ItemService());
                    $character->fresh();

                    $new_node->type = 0;
                    $new_node->save();
                    $new_node->content->delete();
                    $nodes = $nodeService->generateNodes($character);

                    $this->addData(['nodes' => $nodes, 'character' => $character, 'log' => $log->log]);
                    return $this->answer;
                }
        }

        return $this->answer;
    }
}
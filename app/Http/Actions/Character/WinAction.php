<?php 
namespace App\Http\Actions\Character;

use App\Http\Actions\Action;
use Illuminate\Support\Facades\App;
use App\Http\Services\Log;
use App\Models\Character;
use App\Models\Node;
use App\Http\Services\ItemService;
use App\Http\Services\NodeService;
use App\Http\Services\InventoryService;

class WinAction extends Action
{
    public function do($request){

        $character = Character::find($request->char_id);
        $log = App::make(Log::class);

        $node = Node::getNodeByCoord($character->x, $character->y, $character->id);

        if($node->type === Node::TYPE_OBJECT){
            $nodeService = new NodeService();
            $nodeService->takeObject($character, $log, $node, new InventoryService(), new ItemService());
        }
        else{
            $itemService = new ItemService();
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
        $character->addLife($character->life_regeneration);
        $character->addMana($character->mana_regeneration);
        $character->save();

        $node->content->delete();
        $nodeService = new NodeService();
        $nodes = $nodeService->generateNodes($character);
        $this->addData(['nodes' => $nodes,'char' => $character, 'log' => $log->log]);
        
        return $this->answer;
    }
}
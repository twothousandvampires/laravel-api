<?php
namespace App\Http\Controllers\API;

use App\Http\Services\CharacterService;
use App\Http\Services\InventoryService;
use App\Http\Services\PassiveService;
use App\Models\Passives;
use App\Models\PassivesList;
use Illuminate\Support\Facades\App;
use App\Http\Services\ItemService;
use App\Http\Services\NodeService;
use App\Http\Services\Log;
use App\Models\Item;
use App\Models\Character;
use App\Models\Node;
use Illuminate\Http\Request;
use App\Models\SkillList;
use App\Models\Skills;
use App\Models\UsedDetail;

class CharacterController extends BaseController
{
    public function setStarted($char_id)
    {
        Character::where('id', $char_id)->update([
           'started' => 1
        ]);
    }
    // public function useTorch($char_id): \Illuminate\Http\JsonResponse
    // {
    //     $character = Character::find($char_id);
    //     if($character){
    //         if($character->torch > 0){
    //             Node::where('char_id', $character->id)->where('x', $character->x)->where('y', $character->y)->update([
    //                 'visited' => 1
    //             ]);
    //             $character->torch--;
    //             $character->save();
    //             return $this->sendResponse(true);
    //         }
    //         else{
    //             return $this->sendError('have no torches');
    //         }
    //     }
    //     return $this->sendError('char not found');
    // }

    public function upgradePassive($char_id, $passive_id, PassiveService $passiveService): \Illuminate\Http\JsonResponse
    {
        $character = Character::find($char_id);
        $passive = Passives::with('stats')->find($passive_id);

        $cost = $passive->level * $passive->exp_cost;
        
        if($character->exp < $cost){
            return $this->sendError('not enough exp!(need ' . $cost . ')');
        }

        $character->exp -= $cost;
        $passiveService->upgradePassive($character, $passive);
        $character->save();

        return $this->sendResponse($character);
    }

    public function learnPassive($char_id, $passive_id, PassiveService $passiveService){
        $passive = Passives::with('stats')->find($passive_id);
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

        $new = PassivesList::inRandomOrder()
            ->where('fp_req', '<=', $character->fight_potential)
            ->where('sp_req', '<=', $character->sorcery_potential)
            ->where('tp_req', '<=', $character->trick_potential)
            ->whereNotIn('name', $passives)->limit(3)->get();

        foreach ($new as $item){
            Passives::create([
                'char_id' => $char_id,
                'name' => $item->name,
                'exp_cost' => $item->exp_cost,
                'potential_increase' => $item->potential_increase,
            ]);
        }

        return $this->sendResponse(Passives::with('stats')->where('char_id', $char_id)->where('level', 0)->get());
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

    public function retreat($char_id, NodeService $nodeService) {
        $character = Character::find($char_id);

        $character->x = $character->prev_x;
        $character->y = $character->prev_y;
        
        $nodes = $nodeService->generateNodes($character);
        return $this->sendResponse(['nodes' => $nodes,'char' => $character]);
    }

    // public function rest($char_id, $amount = 0, PassiveService $passiveService) {
    //     $log = App::make(Log::class);
    //     $character = Character::find($char_id);
    //     $log->addToLog('you rested ' . $amount * 2 . ' life and '. floor($amount / 2). ' mana');
    //     $character->addlife($amount * 2);
    //     $character->addMana(floor($amount / 2));

    //     $character->food -= $amount;
    //     $chance_to_learn = 10 + $amount;
        
    //     if(mt_rand(0, 100) <= $chance_to_learn){
    //         $type_rnd = mt_rand(0, 100);
    //         if($type_rnd <= 50){
    //             $type_rnd = mt_rand(0, 100);
    //             if($type_rnd <= 50){
    //                 $player_skills = Skills::where('char_id', $char_id)->pluck('skill_name')->toArray();
    //                 $skill = SkillList::whereNotIn('skill_name', $player_skills)
    //                     ->where('fp_req', '<=', $character->fight_potential)
    //                     ->where('sp_req', '<=', $character->sorcery_potential)
    //                     ->where('tp_req', '<=', $character->trick_potential)
    //                     ->inRandomOrder()
    //                     ->first();

    //                 Skills::create([
    //                     'char_id' => $char_id,
    //                     'item_id' => null,
    //                     'skill_name' => $skill['skill_name'],
    //                     'skill_type' => $skill['skill_type'],
    //                     'potential_increase' => $skill['potential_increase'],
    //                     'level' => 1
    //                 ]);

    //                 if($skill->potential_increase != null){
    //                     $character[$skill->potential_increase] += 1;
    //                 }
    //                 $log->addToLog('you learned up ' . $skill->skill_name);
    //             }
    //             else{
    //                 $skill = Skills::where('char_id', $char_id)->where('level','!=', 0)->inRandomOrder()->first();
    //                 if($skill){
    //                     $skill->level ++;
    //                     $skill->save();
    //                 }
    //                 $log->addToLog($skill->skill_name . ' increased level');
    //             }
    //         }
    //         else{
    //             $type_rnd = mt_rand(0, 100);
    //             if($type_rnd <= 50){
    //                 $passive = Passives::with('stats')->where('char_id', $char_id)->where('level', '!=', 0)->inRandomOrder()->first();
    //                 if($passive){
    //                     $passiveService->upgradePassive($character, $passive);
    //                     $log->addToLog($passive->name . ' increased level');
    //                 }
    //             }
    //             else{
    //                 $passives = Passives::where('char_id', $char_id)->pluck('name')->toArray();

    //                 $passive = PassivesList::inRandomOrder()
    //                     ->where('fp_req', '<=', $character->fight_potential)
    //                     ->where('sp_req', '<=', $character->sorcery_potential)
    //                     ->where('tp_req', '<=', $character->trick_potential)
    //                     ->whereNotIn('name', $passives)
    //                     ->first();

    //                 if($passive){
    //                     $passive = Passives::create([
    //                         'char_id' => $char_id,
    //                         'name' => $passive->name,
    //                         'exp_cost' => $passive->exp_cost,
    //                         'potential_increase' => $passive->potential_increase,
    //                     ]);
    //                     $passive->refresh();
    //                 }
            
    //                 $passiveService->upgradePassive($character, $passive);
    //                 $log->addToLog('you learned up ' . $passive->name);
    //             }
    //         }
    //     }
    //     $character->save();
    //     return $this->sendResponse(['char' => $character, 'log' => $log]);
    // }

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
            $character->addLife($character->life_regeneration);
            $character->addMana($character->mana_regeneration);
            $character->save();

            $node->content->delete();

            $nodes = $nodeService->generateNodes($character);
            return $this->sendResponse(['nodes' => $nodes,'char' => $character, 'log' => $log->log]);
        }
        catch (\Exception $e){
            return $e;
        }
    }
}

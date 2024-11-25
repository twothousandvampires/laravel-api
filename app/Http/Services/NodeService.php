<?php

namespace App\Http\Services;
use App\Models\Item;
use App\Models\ItemsList;
use App\Models\Node;
use App\Models\NodeContent;
use App\Models\NodeStats;
use App\Models\SkillList;
use App\Models\Skills;

class NodeService{

    public function checkNode($x, $y, $arr){

        foreach ($arr as $item) {
            if($item->x == $x && $item->y == $y){
                return $item->id;
            }
        }
        return false;
    }
    public function takeObject(&$character, &$log, $node, InventoryService $inventoryService, ItemService $itemService): void
    {
        if($node->content->content_type === NodeContent::OBJECT_TYPE_PALE_OBELISK){
            $character->exp += 1000;
            $character->save();
            $log->addToLog('Your have got 1000 exp');
        }
        else if($node->content->content_type === NodeContent::OBJECT_TYPE_LIFE_SOURCE){
            $r =  mt_rand(8, 10);
            $character->life += $r;
            if($character->life > $character->max_life){
                $character->life = $character->max_life;
            }
            $character->save();
            $log->addToLog("Your have restored $r hp");
        }
        else if($node->content->content_type === NodeContent::OBJECT_TYPE_MANA_SOURCE){
            $r =  mt_rand(2, 4);
            $character->mana += $r;
            if($character->mana > $character->max_mana){
                $character->mana = $character->max_mana;
            }
            $character->save();
            $log->addToLog("Your have restored $r mana");
        }
        else if($node->content->content_type === NodeContent::OBJECT_TYPE_REMAINS_OF_THE_CAMP){
            $t_r =  mt_rand(2, 4);
            $character->torch += $t_r;

            $f_r =  mt_rand(5, 10);
            $character->food += $f_r;

            $character->save();
            $log->addToLog("Your have found $t_r torches and $f_r food");
        }
        else if($node->content->content_type === NodeContent::OBJECT_TYPE_BREWPOTION_POST){
            $rnd = mt_rand(1,2);
            $items = ItemsList::leftJoin('game_data.used_detail_list as udl', 'udl.item_list_id', '=' , 'item_List.id')
                ->where('item_List.type', Item::ITEM_TYPE_USED)
                ->where('udl.used_type', 1)
                ->inRandomOrder()
                ->limit($rnd)
                ->get();

            foreach ($items as $item){
                $slot = $inventoryService->getFreeSlots($character->id);

                if ($slot) {
                    $item = $itemService->createByName($item->name, $character->id, $slot);
                    if($item){
                        $log->addToLog('your found the ' . $item->name);
                    }
                } else {
                    $log->addToLog('you found an item but you don`t have slots for it');
                }
            }
        }
        else if($node->content->content_type === NodeContent::OBJECT_TYPE_FLYING_SCROLLS){
            $item = ItemsList::leftJoin('game_data.used_detail_list as udl', 'udl.item_list_id', '=' , 'item_List.id')
                ->where('item_List.type', Item::ITEM_TYPE_USED)
                ->where('udl.used_type', 4)
                ->inRandomOrder()
                ->first();

            $slot = $inventoryService->getFreeSlots($character->id);

            if ($slot && $item) {
                $item = $itemService->createByName($item->name, $character->id, $slot);
                if($item){
                    $log->addToLog('your found the ' . $item->name);
                }
            } else {
                $log->addToLog('you found an item but you don`t have slots for it');
            }

        }
        else if($node->content->content_type === NodeContent::OBJECT_TYPE_ABANDONED_FORGE){
            $item = ItemsList::leftJoin('game_data.used_detail_list as udl', 'udl.item_list_id', '=' , 'item_List.id')
                ->where('item_List.type', Item::ITEM_TYPE_USED)
                ->where('udl.used_type', 5)
                ->inRandomOrder()
                ->first();

            $slot = $inventoryService->getFreeSlots($character->id);

            if ($slot && $item) {
                $item = $itemService->createByName($item->name, $character->id, $slot);
                if($item){
                    $log->addToLog('your found the ' . $item->name);
                }
            } else {
                $log->addToLog('you found an item but you don`t have slots for it');
            }

        }
        else if($node->content->content_type === NodeContent::OBJECT_TYPE_ALTAR_OF_FORGOTTEN_WARRIOR){
            $rnd = mt_rand(1,3);
            if($rnd === 1) $character->max_life ++;
            if($rnd === 2) $character->physical_damage ++;
            if($rnd === 3) $character->energy ++;

            $skill = SkillList::where('skill_type', 'attack')->inRandomOrder()->first();
            $player_skills = Skills::where('char_id', $character->id)->pluck('skill_name')->toArray();

            if(in_array($skill->skill_name, $player_skills)){
                $skill = Skills::where('char_id', $character->id)->where('skill_name', $skill->skill_name)->first();
                $skill->level ++;
                $skill->save();
                $log->addToLog($skill->skill_name . ' got level');
            }
            else{
                Skills::create([
                    'char_id' => $character->id,
                    'level' => 1,
                    'item_id' => null,
                    'skill_name' => $skill->skill_name,
                    'skill_type' => $skill->skill_type
                ]);
                $log->addToLog('you have learned '. $skill->skill_name);
            }

            $character->save();
        }
        else if($node->content->content_type === NodeContent::OBJECT_TYPE_ALTAR_OF_FORGOTTEN_SORCERER){
            $rnd = mt_rand(1,3);
            if($rnd === 1) $character->max_mana ++;
            if($rnd === 2) $character->magic_damage ++;
            if($rnd === 3) $character->resist ++;

            $skill = SkillList::where('skill_type', 'magic')->inRandomOrder()->first();
            $player_skills = Skills::where('char_id', $character->id)->pluck('skill_name')->toArray();

            if(in_array($skill->skill_name, $player_skills)){
                $skill = Skills::where('char_id', $character->id)->where('skill_name', $skill->skill_name)->first();
                $skill->level ++;
                $skill->save();
                $log->addToLog($skill->skill_name . ' got level');
            }
            else{
                Skills::create([
                    'char_id' => $character->id,
                    'level' => 1,
                    'item_id' => null,
                    'skill_name' => $skill->skill_name,
                    'skill_type' => $skill->skill_type
                ]);
                $log->addToLog('you have learned '. $skill->skill_name);
            }

            $character->save();
        }
    }
    public function torch($character){

        $node = Node::where('char_id',$character->id)
                        ->where('x',$character->x)
                        ->where('y',$character->y)
                        ->first();

        $node->light = 1;
        $node->save();

    }

    public function checkWay($x, $y, $parent_id, $arr){

      $n_node = $this->checkNode($x,$y-1, $arr);
      $s_node = $this->checkNode($x,$y+1, $arr);
      $w_node = $this->checkNode($x-1,$y, $arr);
      $e_node = $this->checkNode($x+1,$y, $arr);

      if($n_node && $n_node !== $parent_id){
          return false;
      }if($s_node && $s_node !== $parent_id){
            return false;
      }if($w_node && $w_node !== $parent_id){
            return false;
      }if($e_node && $e_node !== $parent_id){
            return false;
      }
      return true;

    }

    public function checkFrame($char_x, $char_y, $x , $y){

        if($x > $char_x + 6 || $x < $char_x - 6 || $y > $char_y + 6 || $y < $char_y - 6 ){
            return false;
        }
        return true;

    }

    public function generateSingleNode($x, $y, $links, $char_id): void
    {

        $node = new Node();
        $node->x = $x;
        $node->y = $y;
        $node->links = $links;
        $node->char_id = $char_id;
        $node->type = Node::TYPE_EMPTY;
        $node->visited = 1;
        $node->save();

    }

    public function generateNodes($char): \Illuminate\Database\Eloquent\Collection|array
    {

        $stats = NodeStats::where('char_id', $char->id)->first();
        $node_content_service =  new NodeContentService();
        // node available to link (links != 0)
        $nodes_to_link = Node::getNodes($char->x,$char->y,$char->id,6,true);

        // all nodes
        $all = Node::getNodes($char->x,$char->y,$char->id,6);

        while($nodes_to_link->count()){
            // get the random node
            $parent = $nodes_to_link->random();

            if($parent->links <= 0){
                $nodes_to_link = $nodes_to_link->filter(function ($value, $key) use ($parent) {
                    return $value != $parent;
                });
                continue;
            }

            // define the ways array
            $ways = [];

            // check north, south, east, west nodes for existence
            if(!$this->checkNode($parent->x + 1,$parent->y, $all)){
                $ways[] = [$parent->x + 1,$parent->y, 'e_link','w_link'];
            }
            if(!$this->checkNode($parent->x - 1,$parent->y,$all)){
                $ways[] = [$parent->x - 1,$parent->y , 'w_link','e_link'];
            }
            if(!$this->checkNode($parent->x,$parent->y - 1,$all)){
                $ways[] = [$parent->x,$parent->y - 1 ,'n_link','s_link'];
            }
            if(!$this->checkNode($parent->x,$parent->y + 1,$all)){
                $ways[] = [$parent->x,$parent->y+ 1 ,'s_link','n_link'];
            }

            // check space for direction node
            foreach ($ways as $k => $way){
                if(!$this->checkWay($way[0],$way[1],$parent->id,$all)){
                    unset($ways[$k]);
                }
            }

            // if no ways to link
            if(!count($ways)){
                // kill the link potential
                $parent->links = 0;
                $parent->save();
                $nodes_to_link = $nodes_to_link->filter(function ($value, $key) use ($parent) {
                    return $value != $parent;
                });
                continue;
            }

            // check node link no out of frame
            foreach ($ways as $k => $way){
                if(!$this->checkFrame($char->x,$char->y,$way[0],$way[1])){
                    unset($ways[$k]);
                }
            }

            // if no ways to link
            if(!count($ways)){
                $parent->save();
                $nodes_to_link = $nodes_to_link->filter(function ($value, $key) use ($parent) {
                    return $value != $parent;
                });
                continue;
            }

            // create node Model
            $new_node = new Node();

            // get random way
            $node_way = $ways[array_rand($ways,1)];

            $new_node->x = $node_way[0];
            $new_node->y = $node_way[1];
            $new_node->links = random_int(1,4);
            $new_node->{$node_way[3]} = 1;
            $parent->{$node_way[2]} = 1;
            $new_node->char_id = $char->id;

            $type = mt_rand(0,100);

            if($type >= 80){
                $new_node->type = Node::TYPE_OBJECT;
                $stats->object++;
            }
            else if($type >= 60 && $parent->node_type != Node::TYPE_ENEMY){
                $new_node->type = Node::TYPE_ENEMY;
                $stats->enemy++;
            }
            else if($type >= 40){
                $new_node->type = Node::TYPE_TREASURE;
                $stats->treasure++;
            }
            else{
                $new_node->type = Node::TYPE_EMPTY;
                $stats->empty++;
            }
            $stats->total++;
            $stats->save();
            $new_node->save();
            $node_content_service->createContent($new_node, $char);


            // reduce link potential
            $parent->links -= 1;
            $parent->save();

            // push

            $nodes_to_link->push($new_node);
            $all->push($new_node);
        }

        for($i = 0;$i < $all->count();$i++){
            if ( abs($char->x - $all[$i]->x) <=1 && abs($char->y - $all[$i]->y) <= 1 && !$all[$i]->visited){
                $all[$i]->save();
            }
        }

        return  Node::getNodes($char->x,$char->y,$char->id,6);
    }
}

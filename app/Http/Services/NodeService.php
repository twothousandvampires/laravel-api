<?php

namespace App\Http\Services;
use App\Models\Node;
use App\Models\Enemy;

class NodeService{

    public $node_content_service;

    function __construct(){
        $this->node_content_service = new NodeContentService();
    }

    public function checkNode($x, $y, $arr){

        foreach ($arr as $item) {
            if($item->x == $x && $item->y == $y){
                return $item->id;
            }
        }
        return false;
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

    public function generateSingleNode($x, $y, $links, $char_id){

        $node = new Node();
        $node->x = $x;
        $node->y = $y;
        $node->links = $links;
        $node->char_id = $char_id;
        $node->visited = 1;
        $node->type = Node::TYPE_EMPTY;
        $node->save();

    }

    public function generateNodes($char){

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
                // no potential kill
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

            $type = random_int(0,100);

            if($type >= 90){
                $new_node->type = Node::TYPE_ENEMY;
            }

            else if($type > 80){
                $new_node->type = Node::TYPE_TREASURE;
            }

            else{
                $new_node->type = Node::TYPE_EMPTY;
            }

            if($new_node->save()){
                $this->node_content_service->createContent($new_node);
            }

            // reduce link potential
            $parent->links -= 1;
            $parent->save();

            // if node cannot link, remove them

            // push

            $nodes_to_link->push($new_node);
            $all->push($new_node);
        }

        for($i = 0;$i < $all->count();$i++){
            if ( abs($char->x - $all[$i]->x) <=1 && abs($char->y - $all[$i]->y) <= 1 && !$all[$i]->visited){
                $all[$i]->save();
            }
        }

        return $all->toArray();
    }
}

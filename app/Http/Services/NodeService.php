<?php

namespace App\Http\Services;
use App\Models\Node;

class NodeService{

    /**
     * @var \string[][]
     */
    private $nomenclature_types;

    function __construct(){
        $this->nomenclature_types = [
            ['N','NT','KOP'],
            ['JS','JSS','Q'],
            ['OP','POS','OPP'],
            ['32P','3','1'],
            ['Home','Hearth','Centro'],
        ];
    }

    public function checkNode($x, $y, $arr){

        foreach ($arr as $item) {
            if($item->x == $x && $item->y == $y){
                return $item->id;
            }
        }

        return false;
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
        $node->type = 4;
        $this->Nomenclature($node);
        $node->image_offset_x = round(random_int(-50,50)/100,2);
        $node->image_offset_y = round(random_int(-50,50)/100,2);
        $node->solar_system_image = 'system_' . random_int(1,2);
        $node->save();
    }

    private function Nomenclature($node){



        $distance = round(sqrt(pow($node->x,2) + pow($node->y,2)),2);


        if($node->x != 0 && $node->y != 0) {
            $angle = round(atan($node->x/$node->y),2);
        }
        else { $angle = 0;}


        if(0 < $node->x && 0 < $node->y){
            $angle += 6.14;
        }
        if(0 > $node->x && 0 < $node->y){
            $angle += 6.14;
        }
        if(0 < $node->x && 0 > $node->y){
            $angle += 6.14;
        }

        $nom = $this->nomenclature_types[$node->type][random_int(0,2)];

        $node->nomenclature = $nom . '-' . $distance . '/' . $angle;

    }

    public function generateNodes($char){

        // node available to link (links != 0)
        $nodes_to_link = Node::getNodes($char->x,$char->y,$char->id,6,true);

        // all nodes
        $all = Node::getNodes($char->x,$char->y,$char->id,6);

        while($nodes_to_link->count()){
            // get the random node
            $parent = $nodes_to_link->random();

            // define the ways array
            $ways = [];

            // check north, south, east, west nodes for existence
            if(!$this->checkNode($parent->x + 1,$parent->y, $all)){
                $ways[] = [$parent->x + 1,$parent->y];
            }
            if(!$this->checkNode($parent->x - 1,$parent->y,$all)){
                $ways[] = [$parent->x - 1,$parent->y];
            }
            if(!$this->checkNode($parent->x,$parent->y - 1,$all)){
                $ways[] = [$parent->x,$parent->y - 1];
            }
            if(!$this->checkNode($parent->x,$parent->y + 1,$all)){
                $ways[] = [$parent->x,$parent->y+ 1];
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
            $new_node->links = random_int(0,2);
            $new_node->char_id = $char->id;

            $rnd = random_int(0,100);
            if($rnd > 98) { $new_node->type = 2; }
            else if($rnd > 95) { $new_node->type = 3; }
            else if($rnd > 90) { $new_node->type = 1; }
            else{
               $new_node->type = 0;
            }

            $new_node->image_offset_x = round(random_int(-50,50)/10,2);
            $new_node->image_offset_y = round(random_int(-50,50)/10,2);

            $new_node->solar_system_image = 'system_' . random_int(1,2);

            $this->Nomenclature($new_node);

            $new_node->save();

            // reduce link potential
            $parent->links -= 1;
            $parent->save();

            // if node cannot link, remove them
            if($parent->links == 0){
                $nodes_to_link = $nodes_to_link->filter(function ($value, $key) use ($parent) {
                    return $value != $parent;
                });
            }

            // push
            $nodes_to_link->push($new_node);
            $all->push($new_node);
        }

        return Node::getNodes($char->x,$char->y,$char->id,4)->toArray();
    }
}
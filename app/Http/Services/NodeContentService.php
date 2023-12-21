<?php

namespace App\Http\Services;

use App\Models\enemy;
use App\Models\Node;
use App\Models\NodeContent;

class NodeContentService
{

    public function createContent($node){
        if($node->type == Node::TYPE_EMPTY){
            return;
        }

        $node_content = new NodeContent();
        $node_content->node_id = $node->id;

        if($node->type == Node::TYPE_ENEMY){

            $node_content->content_type = NodeContent::ENEMY_TYPE_UNDEAD;

            $content = json_decode('{}');
            $content->map = $this->generateMap();
            $content->enemy = $this->generateGroup($node_content->content_type);

            $node_content->content = json_encode($content);

        }
        else if($node->type == Node::TYPE_TREASURE){

            $rnd = random_int(0,100);
            if($rnd >= 50){
                $node_content->content_type = NodeContent::TREASURE_TYPE_CHEST;
            }
            else{
                $node_content->content_type = NodeContent::TREASURE_TYPE_SCROLL;
            }


        }

        $node_content->save();
    }

    public function generateGroup($type){

        $all = Enemy::leftJoin('enemy_types as et','enemies.type_id','=','et.id')
                    ->where('enemies.type_id', $type)
                    ->get();

        $group = [];

        foreach ($all as $enemy){
            if($enemy->chance >= random_int(0,100)){
                $count = random_int($enemy->min_count, $enemy->max_count);
                if($count){
                    $squad = [
                        'name' => $enemy->name,
                        'count' => $count,
                        'exp' => $enemy->exp_gain,
                    ];
                    $group[] = $squad;
                }
            }
        }

        return $group;
    }

    public function generateMap(){
        $map = json_decode('{}');
        $size = random_int(500,600);
        $map->width  = $size;
        $map->height = $size;
        return $map;
    }

    public function calcExp($node){
        $content = json_decode($node->content()->first()->content)->enemy;
        $sum = 0;
        foreach ($content as $squad){
            $sum += $squad->count * $squad->exp;
        }
        return $sum;
    }
}

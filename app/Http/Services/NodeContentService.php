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

            $node_content->content_type = NodeContent::TREASURE_TYPE_CHEST;

        }

        $node_content->save();
    }

    public function generateGroup($type){

        switch ($type){
            case 1:
                $group = [];
                $warriors = Enemy::where('name', 'skeleton warrior')->first();
                $warriors->count = random_int(1,1);
                $group[] = $warriors;
                $archers = Enemy::where('name', 'skeleton archer')->first();
                $archers->count = random_int(1,1);
                $group[] = $archers;
                $mages = Enemy::where('name', 'skeleton mage')->first();
                $mages->count = random_int(1,1);
                $group[] = $mages;
                $mages = Enemy::where('name', 'ghost')->first();
                $mages->count = random_int(1,1);
                $group[] = $mages;
                $giant = Enemy::where('name', 'giant undead')->first();
                $giant->count = random_int(1,1);
                $group[] = $giant;
                $lich = Enemy::where('name', 'lich')->first();
                $lich->count = random_int(1,1);
                $group[] = $lich;

                return $group;
        }
    }

    public function generateMap(){
        $map = json_decode('{}');
        $size = random_int(500,600);
        $map->width  = $size;
        $map->height = $size;
        return $map;
    }
}

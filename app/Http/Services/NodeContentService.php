<?php

namespace App\Http\Services;

use App\Models\enemy;
use App\Models\Item;
use App\Models\ItemsList;
use App\Models\Node;
use App\Models\NodeContent;
use Illuminate\Support\Facades\App;

class NodeContentService
{

    public function createContent($node): void
    {
        if($node->type == Node::TYPE_EMPTY){
            return;
        }

        $node_content = new NodeContent();
        $node_content->node_id = $node->id;

        if($node->type == Node::TYPE_ENEMY){

            $node_content->content_type = NodeContent::ENEMY_TYPE_UNDEAD;

            $content = json_decode('{}');
            $content->map = $this->generateMap();
            $content->enemy = $this->generateGroup($node, $node_content->content_type);

            $node_content->content = json_encode($content);

        }
        else if($node->type == Node::TYPE_TREASURE){

            $rnd = random_int(0,100);
            if($rnd >= 0){
                $node_content->content_type = NodeContent::TREASURE_TYPE_CHEST;
            }
            else{
                $node_content->content_type = NodeContent::TREASURE_TYPE_SCROLL;
            }


        }

        $node_content->save();
    }

    public function secondCoverSlotsAvailable($first_line_reserved, $first_line, $second_line)
    {
        if(!count($first_line_reserved)){
            return [];
        }
        $data = [];
        foreach ($first_line_reserved as $reserved){
            if(in_array($reserved + 1, $second_line)){
                $data[] = $reserved + 1;
            }
        }

        return $data;
    }

    public function generateGroup($node, $content_type): array
    {
        $first_line = [5, 12, 19, 26, 33];
        $second_line = [6, 13, 20, 27, 34];
        $third_line = [8, 14, 21, 28, 35];

        $first_line_reserved = [];
        $second_line_reserved = [];

        $max_count = 15;
        $created_count = 0;

        $distance = sqrt(pow($node->x, 2) + pow($node->y, 2));
        $distance = floor($distance/50);

        if($distance > 3){
            $distance = 3;
        }

        $all = Enemy::leftJoin('enemy_types as et','enemies.type_id','=','et.id')
                    ->leftJoin('enemy_count as ec', function ($join) use($distance){
                        $join->on('ec.enemy_id','=','enemies.id')
                        ->where('ec.distance', $distance);

                    })
                    ->where('enemies.type_id', $content_type)
                    ->whereIn('enemies.name', ['skeleton warrior', 'skeleton archer']) // !!!
                    ->get();

        $group = [
            'total_exp' => 0,
            'groups' => [],
        ];

        foreach ($all as $enemy){

            if($created_count >= $max_count){
                continue;
            }

            if($enemy->chance >= random_int(0,100)){
                $count = random_int($enemy->min_count, $enemy->max_count);

                if($created_count + $count > $max_count){
                    $count = $max_count - $created_count;
                }

                if($count){
                    $squad = [];

                    for($i = 0; $i < $count; $i++){
                        if($enemy->line === 1){
                            $num = $first_line[array_rand($first_line)];
                            $first_line_reserved[] = $num;
                            unset($first_line[array_search($num, $first_line)]);
                        }
                        else if($enemy->line === 2){
                            $covered = $this->secondCoverSlotsAvailable($first_line_reserved, $first_line, $second_line);
                            if(count($covered)){
                                $num = $covered[array_rand($covered)];
                                $second_line_reserved[] = $num;
                                unset($second_line[array_search($num, $second_line)]);
                            }
                            else{
                                $num = $second_line[array_rand($second_line)];
                                $second_line_reserved[] = $num;
                                unset($second_line[array_search($num, $second_line)]);
                            }
                        }
                        $squad[] = [
                            'name' => $enemy->name,
                            'num' => $num
                        ];
                    }

                    $created_count += $count;
                    $group['groups'][] = $squad;
                    $group['total_exp'] += $count * $enemy->exp_gain;
                }
            }
        }

        if(random_int(0,100) > 90){
            $log = App::make(Log::class);
            $log->addTolog($this->getMsgToLog($content_type));
        }

        $group['item'] = $this->generateRewardItem($content_type);

        return $group;
    }

    private function getMsgToLog($content_type){
        if($content_type === enemy::ENEMY_TYPE_UNDEAD){
            return 'bones are shaking nearby...';
        }
    }

    private function generateRewardItem($content_type){
        if($content_type === NodeContent::ENEMY_TYPE_UNDEAD){
            $rarity = $this->generateRarity();
            // accessory or gem
            $item = ItemsList::leftJoin('game_data.equip_detail_list as edl', 'edl.item_list_id', '=' , 'item_List.id')
                   ->where('type', Item::ITEM_TYPE_EQUIP)
                   ->where('rarity', $rarity)
                   ->where('edl.equip_type', '=', Item::EQUIP_CLASS_ACCESSORY)
                   ->orWhere('type', Item::ITEM_TYPE_GEM)
                   ->inRandomOrder()
                   ->first();

            return $item ? $item->name : null;
        }
    }

    private function generateRarity(): int
    {
        $random = random_int(0, 100);
        if($random >= 95){
            return 4;
        }
        else if($random >= 80){
            return 3;
        }
        else if($random >= 60){
            return 2;
        }
        return 1;
    }

    public function generateMap(){
        $map = json_decode('{}');
        $size = random_int(500,600);
        $map->width  = $size;
        $map->height = $size;
        return $map;
    }
}

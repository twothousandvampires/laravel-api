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

            $r = mt_rand(1,3);
            $node_content->content_type = $r;

            $content = json_decode('{}');
            $content->enemy = $this->generateGroup($node, $node_content->content_type);

            $node_content->content = json_encode($content);

        }
        else if($node->type == Node::TYPE_TREASURE){
            $content = json_decode('{}');

            $node_content->content_type = NodeContent::generateTreasureContentType();
            $content->item = NodeContent::generateItemForTreasure($node_content->content_type, $this->generateRarity());

            $node_content->content = json_encode($content);
        }
        else if($node->type == Node::TYPE_OBJECT){
            $content = json_decode('{}');
            $node_content->content_type = NodeContent::generateObjectContentType();
            $content->enemy = $this->generateGroupForObject($node, $node_content->content_type);

            $node_content->content = json_encode($content);
        }

        $node_content->save();
    }
    public function generateGroupForObject($node, $node_type): array
    {
        $template = null;
        $distance = sqrt(pow($node->x, 2) + pow($node->y, 2));
        $distance = floor($distance/20);

        if($distance > 3){
            $distance = 3;
        }

        if($node_type === NodeContent::OBJECT_TYPE_PALE_OBELISK){
            $template = Enemy::leftJoin('enemy_count as ec', function ($join){
                $join->on('ec.enemy_id','=','enemies.id');
            })
                ->where('name', 'fantasm')
                ->get()[0];

            $template->chance = 100;
            $template->min_count *= $distance + 1;
            $template->max_count *= $distance + 1;
        }

        else if($node_type === NodeContent::OBJECT_TYPE_BREWPOTION_POST){
            $template = Enemy::leftJoin('enemy_count as ec', function ($join){
                $join->on('ec.enemy_id','=','enemies.id');
            })
                ->where('name', 'greenskin potion thwower')
                ->get()[0];

            $template->chance = 100;
            $template->min_count *= $distance + 1;
            $template->max_count *= $distance + 1;
        }

        else if($node_type === NodeContent::OBJECT_TYPE_FLYING_SCROLLS){
            $template = Enemy::leftJoin('enemy_count as ec', function ($join){
                $join->on('ec.enemy_id','=','enemies.id');
            })
                ->where('name', 'bones of sorcerer')
                ->get()[0];

            $template->chance = 100;
            $template->min_count *= $distance + 1;
            $template->max_count *= $distance + 1;
        }
        else if($node_type === NodeContent::OBJECT_TYPE_ABANDONED_FORGE){
            $enemies = Enemy::leftJoin('enemy_count as ec', function ($join){
                $join->on('ec.enemy_id','=','enemies.id');
            })
                ->whereIn('name', ['enchanted armour', 'enchanted weapon'])
                ->where('distance', $distance)
                ->get();

            foreach ($enemies as $enemy){
                $enemy->chance = 100;
                $enemy->min_count *= $distance + 1;
                $enemy->max_count *= $distance + 1;
            }
            return $this->compactGroup($enemies, $distance);
        }

        return $this->compactGroup([$template], $distance);
    }

    public function secondCoverSlotsAvailable($first_line_reserved, $second_line): array
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
    public function compactGroup($group_array, $distance): array
    {
        $first_line = [5, 12, 19, 26, 33];
        $second_line = [6, 13, 20, 27, 34];
        $third_line = [7, 14, 21, 28, 35];

        $first_line_reserved = [];
        $second_line_reserved = [];

        $max_count = 4;

        if($distance == 1){
            $max_count = 8;
        }
        else if($distance == 2){
            $max_count = 12;
        }
        else if($distance == 3){
            $max_count = 15;
        }

        $created_count = 0;

        $group = [
            'total_count' => 0,
            'total_exp' => 0,
            'groups' => [],
        ];

        if(!$group_array[0]){
            return $group;
        }

        foreach ($group_array as $enemy){

            if($created_count >= $max_count){
                break;
            }

            $count = random_int($enemy->min_count, $enemy->max_count);
            $squad = [];

            for($i = 0; $i < $count; $i++){
                if($created_count >= $max_count || random_int(0,100) >  $enemy->chance){
                    continue;
                }
                if($enemy->line === 1){
                    if(count($first_line)){
                        $num = $first_line[array_rand($first_line)];
                        $first_line_reserved[] = $num;
                        unset($first_line[array_search($num, $first_line)]);
                        $created_count++;
                    }
                    else if(count($second_line)){
                        $num = $second_line[array_rand($second_line)];
                        $second_line_reserved[] = $num;
                        unset($second_line[array_search($num, $second_line)]);
                        $created_count++;
                    }
                    else if(count($third_line)){
                        $num = $third_line[array_rand($third_line)];
                        unset($third_line[array_search($num, $third_line)]);
                        $created_count++;
                    }
                }
                else if($enemy->line === 2){
                    if(count($second_line)){
                        $covered = $this->secondCoverSlotsAvailable($first_line_reserved, $first_line);
                        if(count($covered)){
                            $num = $covered[array_rand($covered)];
                        }
                        else{
                            $num = $second_line[array_rand($second_line)];
                        }
                        $second_line_reserved[] = $num;
                        unset($second_line[array_search($num, $second_line)]);
                        $created_count++;
                    }
                    else if(count($third_line)){
                        $num = $third_line[array_rand($third_line)];
                        unset($third_line[array_search($num, $third_line)]);
                        $created_count++;
                    }
                    else if(count($first_line)){
                        $num = $first_line[array_rand($first_line)];
                        unset($first_line[array_search($num, $first_line)]);
                        $created_count++;
                    }
                }
                else if($enemy->line === 3){
                    if(count($third_line)){
                        $covered = $this->secondCoverSlotsAvailable($second_line, $second_line_reserved);
                        if(count($covered)){
                            $num = $covered[array_rand($covered)];
                        }
                        else{
                            $num = $third_line[array_rand($third_line)];
                        }
                        unset($third_line[array_search($num, $third_line)]);
                        $created_count++;
                    }
                    else if(count($second_line)){
                        $num = $second_line[array_rand($second_line)];
                        unset($second_line[array_search($num, $second_line)]);
                        $created_count++;
                    }
                    else if(count($first_line)){
                        $num = $first_line[array_rand($first_line)];
                        unset($first_line[array_search($num, $first_line)]);
                        $created_count++;
                    }
                }

                $squad[] = [
                    'name' => $enemy->name,
                    'num' => $num
                ];

            }

            $group['groups'][] = $squad;
            $group['total_exp'] += count($squad) * $enemy->exp_gain;
            $group['total_count'] += count($squad);

        }
        return $group;
    }
    public function generateGroup($node, $type): array
    {
        $distance = sqrt(pow($node->x, 2) + pow($node->y, 2));
        $distance = floor($distance/25);

        if($distance > 3){
            $distance = 3;
        }

        $group_array = enemy::getEnemyByDistance($type, $distance);
        $group = $this->compactGroup($group_array, $distance);

        if(random_int(0,100) > 90){
            $log = App::make(Log::class);
            $log->addTolog($this->getMsgToLog($type));
        }

        $group['item'] = $this->generateRewardItem($type);

        return $group;
    }
    private function getMsgToLog($content_type){
        if($content_type === enemy::ENEMY_TYPE_UNDEAD){
            return 'bones are shaking nearby...';
        }
    }
    private function generateRewardItem($content_type){
        $rnd = mt_rand(0, 100);
        if($content_type === NodeContent::ENEMY_TYPE_UNDEAD){
            if($rnd > 40){
                return null;
            }
            $rarity = $this->generateRarity();

            $item = ItemsList::leftJoin('game_data.equip_detail_list as edl', 'edl.item_list_id', '=' , 'item_List.id')
                   ->where('type', Item::ITEM_TYPE_EQUIP)
                   ->where('rarity', $rarity)
                   ->where('edl.equip_type', '=', Item::EQUIP_CLASS_ACCESSORY)
                   ->inRandomOrder()
                   ->first();

            return $item ? $item->name : null;
        }
        else if($content_type === NodeContent::ENEMY_TYPE_GREENSKINS){
            if($rnd > 60){
                return null;
            }
            $rarity = $this->generateRarity();
            $item = ItemsList::leftJoin('game_data.equip_detail_list as edl', 'edl.item_list_id', '=' , 'item_List.id')
                ->where('type', Item::ITEM_TYPE_EQUIP)
                ->where('rarity','<=', $rarity)
                ->where('edl.equip_class', 3)
                ->inRandomOrder()
                ->first();

            return $item ? $item->name : null;
        }
    }
    private function generateRarity(): int
    {
        $random = random_int(0, 100);
        if($random >= 90){
            return 4;
        }
        else if($random >= 70){
            return 3;
        }
        else if($random >= 35){
            return 2;
        }
        return 1;
    }
}

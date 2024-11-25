<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NodeContent extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $connection = 'world';
    protected $table = 'node_content';

    public const ENEMY_TYPE_UNDEAD = 1;
    public const ENEMY_TYPE_GREENSKINS = 2;
    public const ENEMY_TYPE_LIVING_CREATURES = 3;

    public const TREASURE_TYPE_CHEST = 1;
    public const TREASURE_TYPE_SCROLL = 2;
    public const TREASURE_TYPE_CRYSTAL_VEIN = 3;
    public const TREASURE_TYPE_DEAD_BODY = 4;
    public const TREASURE_TYPE_GHOSTLY_MAGE = 5;
    public const TREASURE_TYPE_OUTER_LIFE = 6;
    public const TREASURE_TYPE_GHOSTLY_WARRIOR = 7;

    public const OBJECT_TYPE_PALE_OBELISK = 1;
    public const OBJECT_TYPE_LIFE_SOURCE = 2;
    public const OBJECT_TYPE_BREWPOTION_POST = 3;
    public const OBJECT_TYPE_ALTAR_OF_FORGOTTEN_WARRIOR = 4;
    public const OBJECT_TYPE_ALTAR_OF_FORGOTTEN_SORCERER = 5;
    public const OBJECT_TYPE_MANA_SOURCE = 6;
    public const OBJECT_TYPE_REMAINS_OF_THE_CAMP = 7;
    public const OBJECT_TYPE_FLYING_SCROLLS = 8;
    public const OBJECT_TYPE_ABANDONED_FORGE = 9;

    private const TREASURE_TYPE_RARITY = [
        1 => [NodeContent::TREASURE_TYPE_DEAD_BODY, NodeContent::TREASURE_TYPE_OUTER_LIFE],
        2 => [NodeContent::TREASURE_TYPE_CRYSTAL_VEIN, NodeContent::TREASURE_TYPE_CHEST],
        3 => [NodeContent::TREASURE_TYPE_GHOSTLY_MAGE, NodeContent::TREASURE_TYPE_GHOSTLY_WARRIOR]
    ];

    private const OBJECT_TYPE_RARITY = [
        1 => [NodeContent::OBJECT_TYPE_LIFE_SOURCE, NodeContent::OBJECT_TYPE_MANA_SOURCE, NodeContent::OBJECT_TYPE_REMAINS_OF_THE_CAMP],
        2 => [NodeContent::OBJECT_TYPE_BREWPOTION_POST,
            NodeContent::OBJECT_TYPE_ALTAR_OF_FORGOTTEN_WARRIOR,
            NodeContent::OBJECT_TYPE_ALTAR_OF_FORGOTTEN_SORCERER
        ],
        3 => [NodeContent::OBJECT_TYPE_PALE_OBELISK, NodeContent::OBJECT_TYPE_FLYING_SCROLLS,NodeContent::OBJECT_TYPE_ABANDONED_FORGE]
    ];

    static function generateItemForTreasure($type, $rarity){
        if($type === NodeContent::TREASURE_TYPE_CHEST){
            return ItemsList::where('rarity', '<=', $rarity)->inRandomOrder()->first()->name;
        }
        else if($type === NodeContent::TREASURE_TYPE_DEAD_BODY){
            $item = ItemsList::leftJoin('game_data.equip_detail_list as edl', 'edl.item_list_id', '=' , 'item_List.id')
                ->leftJoin('game_data.used_detail_list as udl', 'udl.item_list_id', '=' , 'item_List.id')
                ->where('item_List.type', Item::ITEM_TYPE_EQUIP)
                ->where('rarity', '<=', $rarity)
                ->whereIn('edl.equip_type', [Item::EQUIP_CLASS_ARMOUR, Item::EQUIP_CLASS_WEAPON])
                ->orWhere('item_List.type', Item::ITEM_TYPE_USED)
                ->where('udl.used_type', 1)
                ->where('rarity', '<=', $rarity)
                ->inRandomOrder()
                ->first();

            return $item ? $item->name : null;
        }
        else if($type === NodeContent::TREASURE_TYPE_CRYSTAL_VEIN){
            return ItemsList::getRandomGemName($rarity);
        }
        else if($type === NodeContent::TREASURE_TYPE_GHOSTLY_MAGE){
            $item = ItemsList::leftJoin('game_data.equip_detail_list as edl', 'edl.item_list_id', '=' , 'item_List.id')
                ->where('item_List.type', Item::ITEM_TYPE_EQUIP)
                ->where('rarity', '<=', $rarity)
                ->where('edl.equip_class', 2)
                ->inRandomOrder()
                ->first();

            return $item ? $item->name : null;
        }
        else if($type === NodeContent::TREASURE_TYPE_OUTER_LIFE){
            $item = ItemsList::leftJoin('game_data.used_detail_list as udl', 'udl.item_list_id', '=' , 'item_List.id')
                ->where('item_List.type', Item::ITEM_TYPE_USED)
                ->where('rarity', '<=', $rarity)
                ->where('udl.used_type', 2)
                ->inRandomOrder()
                ->first();

            return $item ? $item->name : null;
        }
        else if($type === NodeContent::TREASURE_TYPE_GHOSTLY_WARRIOR){
            $item = ItemsList::leftJoin('game_data.equip_detail_list as edl', 'edl.item_list_id', '=' , 'item_List.id')
                ->where('item_List.type', Item::ITEM_TYPE_EQUIP)
                ->where('rarity', '<=', $rarity)
                ->where('edl.equip_class', 1)
                ->inRandomOrder()
                ->first();

            return $item ? $item->name : null;
        }
        return null;
    }
    static function generateObjectContentType(): int
    {
        $rnd = mt_rand(0, 100);
        $rarity = 1;
        if($rnd <= 10) $rarity = 3;
        else if($rnd <= 40) $rarity = 2;

        $variants = NodeContent::OBJECT_TYPE_RARITY[$rarity];
        return $variants[array_rand($variants)];
    }
    static function generateTreasureContentType(): int
    {
        $rnd = mt_rand(0, 100);
        $rarity = 1;
        if($rnd <= 10) $rarity = 3;
        else if($rnd <= 40) $rarity = 2;

        $variants = NodeContent::TREASURE_TYPE_RARITY[$rarity];
        $type = $variants[array_rand($variants)];

        return $type;
    }

    public function node(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Node::class);
    }
}

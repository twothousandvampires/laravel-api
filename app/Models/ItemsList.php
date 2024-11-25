<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsList extends Model
{
    private static array $GEMS_LIST = [
        'learning stone',
        'improving stone',
        'unpredictable stone'
    ];

    static function getRandomGemName($rarity){
        $item = ItemsList::leftJoin('game_data.used_detail_list as udl', 'udl.item_list_id', '=' , 'item_List.id')
            ->where('udl.used_type', 3)
            ->where('rarity','<=', $rarity)
            ->where('item_List.type', 3)
            ->inRandomOrder()
            ->first();

        return $item ? $item->name : null;
    }
    use HasFactory;

    protected $table = 'game_data.item_list';

}


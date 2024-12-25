<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpseclib3\Crypt\DES;

class enemy extends Model
{
    use HasFactory;

    public const ENEMY_TYPE_UNDEAD = 1;

    protected $table = 'enemies';
    public $timestamps = false;

    static function getEnemyByDistance($content_type, $distance){

        if($distance > 3){
            $distance = 3;
        }
        
        return Enemy::leftJoin('enemy_types as et','enemies.type_id','=','et.id')
            ->leftJoin('enemy_count as ec', function ($join) use($distance){
                $join->on('ec.enemy_id','=','enemies.id')
                    ->where('ec.distance', $distance);

            })
            ->when($content_type <= 4, function($query) use($content_type) {
                $query->where('enemies.type_id', $content_type);
            })
            ->whereNotNull('ec.distance')
            ->inRandomOrder()
            ->get();
    }
}

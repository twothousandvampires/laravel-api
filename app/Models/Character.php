<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    private $max_inv = 20;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'detail','user_id'
    ];

    static function getFreeInvSlots($char_id){
        $free = [];
        $weapon = Weapon::where('char_id',$char_id)->
                whereNotNull('inv_slot')->get()->pluck('inv_slot')->toArray();
        return array_merge($free, $weapon);
    }

}

<?php
namespace App\Models;

use App\Models\Item;
use App\Models\Skill;
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

    public function items()
    {
        return $this->hasMany(Item::class,'char_id','id');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class,'char_id','id');
    }

}

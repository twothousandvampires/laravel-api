<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    private $max_inv = 20;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'detail','user_id','x','y'
    ];

    public function getItems()
    {
        $this->items = $this->hasMany(Item::class,'char_id','id')->get()
        ->map(function ($item){
            return $item->details();
        });
        return $this;
    }

    public function addExp($exp_count){
       $this->exp += $exp_count;
       $this->save();
    }
}

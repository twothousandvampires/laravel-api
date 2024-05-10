<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected $appends = ['items'];

    public function addExp($node_content){
       $this->exp += json_decode($node_content->content)->enemy->total_exp;;
       $this->save();
    }

    public function getItemsAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->hasMany(Item::class, 'char_id', 'id')->get();
    }
}

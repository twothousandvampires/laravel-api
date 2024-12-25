<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    private $max_inv = 24;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'detail', 'user_id', 'x', 'y'
    ];

    protected $appends = ['items', 'passives', 'skills'];
    public function getFood(){
        if($this->food == 0){
            $this->move_without_food ++;
            if($this->move_without_food >= 10){
                $this->dead = 1;
            }
        }
        else{
            $this->move_without_food = 0;
            $this->food--;
        }
    }
    public function addExp($node_content)
    {
        $decoded = json_decode($node_content->content);
        $this->exp += $decoded->enemy->total_exp;
        $this->enemies_killed += $decoded->enemy->total_count;
    }

    public function addLife($amount){
        $this->life += $amount;
        if($this->life > $this->max_life){
            $this->life = $this->max_life;
        }
    }

    public function addMana($amount){
        $this->mana += $amount;
        if($this->mana > $this->max_mana){
            $this->mana = $this->max_mana;
        }
    }

    public function getItemsAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->hasMany(Item::class, 'char_id', 'id')->get();
    }
    public function getPassivesAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->hasMany(Passives::class, 'char_id', 'id')->with('stats')->get();
    }
    public function getSkillsAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->hasMany(Skills::class, 'char_id', 'id')->where('level','!=', 0)->get();
    }
}

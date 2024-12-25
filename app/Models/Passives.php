<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passives extends Model
{
    protected $table = 'game_serve.passives';
    protected $fillable = ['name', 'char_id', 'exp_cost' , 'potential_increase', 'level'];
    public $timestamps = false;

    public function character(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function stats()
    {
        return $this->hasMany(PassiveStats::class, 'passive_name', 'name');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passives extends Model
{
    protected $table = 'game_serve.passives';
    protected $fillable = ['name', 'char_id', 'stat', 'exp_cost' , 'add_per_level', 'description'];
    public $timestamps = false;

    public function character(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}


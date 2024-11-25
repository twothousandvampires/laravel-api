<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    protected $table = 'game_serve.skills';
    public $timestamps = false;
    protected $fillable = ['char_id', 'item_id', 'skill_name', 'level', 'skill_type'];
}

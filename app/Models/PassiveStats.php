<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PassiveStats extends Model
{
    protected $table = 'game_serve.passive_stats';
    public $timestamps = false;
}
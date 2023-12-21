<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enemy extends Model
{
    use HasFactory;

    public const  ENEMY_TYPE_UNDEAD = 1;

    protected $table = 'enemies';
    public $timestamps = false;
}

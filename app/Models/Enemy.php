<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enemy extends Model
{
    use HasFactory;

    protected $table = 'enemies';
    public $timestamps = false;
}

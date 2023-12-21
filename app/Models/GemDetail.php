<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GemDetail extends Model
{

    use HasFactory;
    protected $table = 'game_serve.gem_details';

    public $timestamps = false;
    protected $fillable = ['item_id','gem_type','gem_class','gem_quality'];

}

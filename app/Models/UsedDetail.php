<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedDetail extends Model
{

    use HasFactory;
    protected $table = 'game_serve.used_details';
    protected $fillable = ['used_type','item_id', 'power'];
    public $timestamps = false;
}


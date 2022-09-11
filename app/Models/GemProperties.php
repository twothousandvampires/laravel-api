<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GemProperties extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'gem_properties';

    protected $fillable = ['item_id','level','name','description','type','exp_needed','order_by'];
}

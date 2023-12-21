<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GemPropertyList extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'gem_property_list';

}

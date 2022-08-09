<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Property;

class Item extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['char_id','name','slot','type','class','price','img_path','subclass','quality'];

    public function properties()
        {
            return $this->hasMany(Property::class,'item_id','id');
        }
    }

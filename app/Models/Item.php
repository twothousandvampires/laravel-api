<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['char_id','name','slot','type','class','price','img_path','quality','property_count',
                            '1_property_name','1_property_value','1_property_stat',
                            '2_property_name','2_property_value','2_property_stat',
                            '3_property_name','3_property_value','3_property_stat',
                            '4_property_name','4_property_value','4_property_stat',];
    }

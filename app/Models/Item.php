<?php

namespace App\Models;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GemProperties;


class Item extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['char_id','name','slot','type','class','price','img_path','subclass','quality'];

    public function props(){
        switch ($this->type){
            case 'skill_gem':
                $this->properties = $this->hasMany(GemProperties::class,'item_id','id')->get();
                break;
            case 'equip':
                $this->properties = $this->hasMany(Property::class,'item_id','id')->get();
                break;
        }
        return $this;
    }
}

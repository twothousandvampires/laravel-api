<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipDetail extends Model
{
    use HasFactory;

    protected $table = 'game_serve.equip_details';
    public $timestamps = false;
    protected $fillable = ['item_id', 'equip_type', 'equip_class', 'equip_quality'];

    public function props()
    {
        return $this->hasMany(Property::class, 'item_id', 'id')->get();
    }
}

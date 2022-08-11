<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillProperties extends Model
{
    use HasFactory;
    protected $table = 'skill_properties';
    public $timestamps = false;
}

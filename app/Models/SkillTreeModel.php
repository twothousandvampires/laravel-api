<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Services\SkillTree;

class SkillTreeModel extends Model
{
    use HasFactory;
    protected $table = 'skill_tree';
    public $timestamps = false;

    static function make($char_id){
        $sk = new SkillTreeModel();
        $sk->char_id = $char_id;
        $sk->body = json_encode(new SkillTree());
        $sk->save();
    }
}

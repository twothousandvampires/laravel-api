<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    use HasFactory;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'detail','user_id'
    ];

    static public function getNodes($x,$y,$char_id,$radius,$link = false){
        $x_min = $x - $radius;
        $x_max = $x + $radius;
        $y_min = $y - $radius;
        $y_max = $y + $radius;

        $res = Node::where('char_id',$char_id)
             ->where('x','>=',$x_min)
            ->where('x','<=',$x_max)
            ->where('y','>=',$y_min)
            ->where('y','<=',$y_max)
            ->where('links', $link ? '!=' : '>=',0)->get();

        return $res;
    }
    static public function getNodeByCoord($x , $y ,$char_id){
        $node = Node::where('x',$x)->where('y',$y)->where('char_id',$char_id)->first();
        return $node;
    }
}

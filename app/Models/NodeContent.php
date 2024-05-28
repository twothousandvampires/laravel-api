<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NodeContent extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $connection = 'world';
    protected $table = 'node_content';

    public const ENEMY_TYPE_UNDEAD = 1;
    public const TREASURE_TYPE_CHEST = 1;
    public const TREASURE_TYPE_SCROLL = 2;
    public const TREASURE_TYPE_CRYSTAL_VEIN = 3;

    protected $fillable = [

    ];

    public function node(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Node::class);
    }
}

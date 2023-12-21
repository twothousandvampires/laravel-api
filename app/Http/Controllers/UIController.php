<?php

namespace App\Http\Controllers;

use App\Http\Services\ItemService;
use App\Http\Services\NodeService;
use App\Http\Services\InventoryService;
use App\Http\Services\SkillService;
use App\Models\Node;


class UIController extends Controller
{
    public function __construct(){
        $this->node_service = new NodeService();
        $this->item_service = new ItemService();
        $this->inv_service = new InventoryService();
        $this->skill_service = new SkillService();
    }

    public function index(){
        $r = \DB::select(\DB::raw('select * from game_serve.characters'));
var_dump($r);
    }

    public function move($direction){


    }
}

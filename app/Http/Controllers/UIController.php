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
    }

    public function index(){
        echo 1;
    }
}

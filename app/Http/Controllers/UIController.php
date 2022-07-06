<?php

namespace App\Http\Controllers;

use App\Http\Services\ItemService;
use App\Http\Services\NodeService;
use App\Http\Services\InventoryService;
use App\Http\Services\SkillService;
use App\Http\Services\SkillTree;
use App\Models\Character;
use Illuminate\Http\Request;
use App\Models\Node;
use App\Models\SkillTreeModel;

class UIController extends Controller
{
    public function __construct(){
        $this->node_service = new NodeService();
        $this->item_service = new ItemService();
        $this->inv_service = new InventoryService();
        $this->skill_service = new SkillService();
    }

    public function index(){

        var_dump($this->item_service->createRandomItem());

    }

    public function move($direction){

        $char = Character::find(109);

        switch ($direction){
            case 0;
                $char->y -= 1;
            break;
            case 1;
                $char->x += 1;
            break;
            case 2;
                $char->y += 1;
            break;
            case 3;
                $char->x -= 1;
            break;
        }
        $char->save();

        return redirect()->route('main');

    }
}

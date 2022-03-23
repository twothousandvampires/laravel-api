<?php

namespace App\Http\Controllers;

use App\Http\Services\ItemService;
use App\Http\Services\NodeService;
use App\Models\Character;
use Illuminate\Http\Request;
use App\Models\Node;


class UIController extends Controller
{
    public function __construct(){

        $this->node_service = new NodeService();
        $this->item_service = new ItemService();
    }

    public function index(){

        $char = Character::find(103);

        $nodes = $this->node_service->generateNodes($char);

        var_dump(Character::getFreeInvSlots(103));die;

        $this->item_service->createRandomWeapon();

        return view('ui',['data'=>$nodes]);
    }

    public function move($direction){

        $char = Character::find(81);

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

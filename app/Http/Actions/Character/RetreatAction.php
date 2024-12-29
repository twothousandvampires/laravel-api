<?php 
namespace App\Http\Actions\Character;

use App\Models\Character;
use App\Http\Services\NodeService;
use App\Http\Actions\Action;

class RetreatAction extends Action
{
    
    public function do($request){

        $character = Character::find($request->char_id);
        $nodeService = new NodeService();

        $character->x = $character->prev_x;
        $character->y = $character->prev_y;
        
        $nodes = $nodeService->generateNodes($character);
        $this->addData(['nodes' => $nodes,'char' => $character]);
        
        return $this->answer;
    }
}
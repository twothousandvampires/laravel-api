<?php 
namespace App\Http\Actions\Character;

use App\Models\Character;
use App\Http\Services\NodeService;
use App\Http\Actions\Action;

class GetCharacterAction extends Action
{
    
    public function do($request){

        $nodeService = new NodeService();
        $character = Character::find($request->char_id);
        $nodes = $nodeService->generateNodes($character);

        $this->addData(['char' => $character, 'nodes' => $nodes]);
        
        return $this->answer;
    }
}
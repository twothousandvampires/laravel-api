<?php 
namespace App\Http\Actions\Character;

use App\Models\Character;
use App\Http\Services\CharacterService;
use App\Http\Actions\Action;

class CreateCharacter extends Action
{

    public $character = false;

    public function do($request){
        $characterService = new CharacterService();
        $char = $characterService->createCharacter($request);
        $this->addData(['char' => $char]);
        
        return $this->answer;
    }
}
<?php 
namespace App\Http\Actions\Character;

use App\Models\Character;
use App\Http\Actions\Action;

class DeleteCharacterAction extends Action
{
    public $character = false;

    public function do($request){

        $character = Character::find($request->delete_id);
        $character->delete();
        
        return $this->answer;
    }
}
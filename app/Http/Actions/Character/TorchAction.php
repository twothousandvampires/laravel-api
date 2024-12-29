<?php 
namespace App\Http\Actions\Character;

use App\Models\Character;
use App\Models\Node;
use App\Http\Actions\Action;

class TorchAction extends Action
{
    public function do($request){

        $character = Character::find($request->char_id);

        if($character->torch > 0){
            Node::where('char_id', $character->id)->where('x', $character->x)->where('y', $character->y)->update([
                'visited' => 1
            ]);
            $character->torch--;
            $character->save();
        }
        else{
            $this->setUnsuccess('have no torches');
        }
       
        return $this->answer;
    }
}
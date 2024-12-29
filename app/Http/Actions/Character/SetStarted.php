<?php 
namespace App\Http\Actions\Character;

use App\Http\Actions\Action;

use App\Models\Character;

class SetStarted extends Action
{
    public function do($request){

        Character::where('id', $request->char_id)->update([
            'started' => 1
         ]);
       
        return $this->answer;
    }
}
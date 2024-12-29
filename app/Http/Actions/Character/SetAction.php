<?php 
namespace App\Http\Actions\Character;

use App\Models\Character;
use App\Http\Actions\Action;

class SetAction extends Action
{
    public function do($request){

        $character = Character::where('id', $request->char_id)->update([
            'life' => $request->life,
            'mana' => $request->mana,
            'dead' => $request->daed
        ]);
      
        return $this->answer;
    }
}
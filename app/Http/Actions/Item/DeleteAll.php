<?php 
namespace App\Http\Actions\Item;

use App\Models\Item;

use App\Http\Actions\Action;

class DeleteAll extends Action
{

    public function do($request){

        Item::where('char_id', $request->char_id)->delete();
      
        return $this->answer;
    }
}
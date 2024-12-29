<?php 
namespace App\Http\Actions\Item;

use App\Models\ItemsList;

use App\Http\Actions\Action;

class GetItemsList extends Action
{

    public function do($request){

        $this->addData(['items' => ItemsList::all()]);

        return $this->answer;
    }
}
<?php 
namespace App\Http\Actions\Craft;

use App\Models\Item;
use App\Models\ItemsList;
use App\Models\Character;
use App\Http\Services\ItemService;
use App\Http\Actions\Action;

class Synthesis extends Action
{
    public function do($request){

        $character = Character::find($request->char_id);
        
        $base_chance = 25;
        $chance = $character->synthesis + $base_chance;

        $items = Item::where('char_id', $character->id)
        ->where('type', 1)
        ->where('slot', '>', 8)
        ->orderBy('slot')
        ->limit(3)
        ->get();

        if(count($items) === 3){
            $ids = $items->map(function($elem){
                return $elem->id;
            });
            Item::whereIn('id', $ids)->delete();
            if($chance >= mt_rand(0, 100)){

                $name = ItemsList::where('type', 1)->inRandomOrder()->pluck('name')->toArray();
                $name = $name[array_rand($name)];

                $itemService = new ItemService();
                $itemService->createByName($name, $character->id);
            }
            
            $this->addData(['items' => Item::where('char_id', $character->id)->get()]);
            return $this->answer;
        }
        else{
            $this->setUnsuccess('not enough items');
            return $this->answer;
        }
    }
}
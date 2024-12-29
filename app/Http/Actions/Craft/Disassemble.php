<?php 
namespace App\Http\Actions\Craft;

use App\Models\Item;
use App\Models\ItemsList;
use App\Http\Services\ItemService;
use App\Http\Actions\Action;
use App\Models\Character;

class Disassemble extends Action
{
    public function do($request){

        $character = Character::find($request->char_id);
    
        $base_chance = 15;
        $chance = $base_chance + $character->splitting;

        $items = Item::where('char_id', $character->id)
        ->where('slot', '>', 8)
        ->where('type', 1)
        ->orderBy('slot')
        ->limit(1)
        ->get();

        if(count($items) === 1){
            $ids = $items->map(function($elem){
                return $elem->id;
            });
            Item::whereIn('id', $ids)->delete();

            if($chance >= mt_rand(0, 100)){
                
                $name = ItemsList::whereIn('name', ['improving dust', 'equipment parts', 'scroll design'])->pluck('name')->toArray();
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
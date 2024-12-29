<?php 
namespace App\Http\Actions\Skill;

use App\Models\Character;
use App\Models\Skills;
use App\Http\Actions\Action;
use App\Models\Item;
use App\Http\Services\ItemService;

class UpgradeRandomSkill extends Action
{
    public $requared_params = [
        'used_id'
    ];

    public function do($request){

        $itemService = new ItemService();
        $used = Item::find($request->used_id);
        $itemService->useUsed($used);

        $skill = Skills::where('char_id', $request->char_id)->where('level', '!=', 0)->inRandomOrder()->first();
        if($skill){
            $skill->level ++;
            $skill->save();
            $character = Character::find($request->char_id);
            $this->addData(['char' => $character]);

            return $this->answer;
        }
        else{
            $this->setUnsuccess('no skill');
            
            return $this->answer;
        }
    }
}
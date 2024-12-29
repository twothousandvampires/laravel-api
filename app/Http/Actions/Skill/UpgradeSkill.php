<?php 
namespace App\Http\Actions\Skill;

use App\Models\Character;
use App\Models\Skills;
use App\Models\Item;
use App\Http\Actions\Action;
use App\Http\Services\ItemService;

class UpgradeSkill extends Action
{
    public $requared_params = [
        'used_id',
        'skill_id'
    ];

    public function do($request){

        $itemService = new ItemService();
        $used = Item::find($request->used_id);
        $itemService->useUsed($used);

        $skill = Skills::find($request->skill_id);
        
        if($skill){
            $skill->level ++;
            $skill->save();
            $character = Character::find($skill->char_id);

            $this->addData(['char' => $character]);
            return $this->answer;
        }
        else{
            $this->setUnsuccess('no skill to upgrade');
            return $this->answer;
        }
    }
}
<?php 
namespace App\Http\Actions\Skill;

use App\Models\Character;
use App\Models\Skills;
use App\Models\Item;
use App\Http\Actions\Action;
use App\Http\Services\ItemService;

class LearnSkill extends Action

{
    public $requared_params = [
        'used_id',
        'skill_id'
    ];

    public function do($request){

        $character = Character::find($request->char_id);

        $skill = Skills::find($request->skill_id);
        
        $skill->level ++;
        $skill->item_id = null;
        $skill->save();

        if($skill->potential_increase != null){
            $character[$skill->potential_increase] += 1;
        }

        $character->save();
        $used = Item::find($request->used_id);

        $itemService = new ItemService();
        $itemService->useUsed($used);

        $this->addData(['char' => $character]);

        return $this->answer;
    }
}
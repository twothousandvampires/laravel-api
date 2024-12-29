<?php 
namespace App\Http\Actions\Skill;

use App\Models\Character;
use App\Models\Skills;
use App\Models\SkillList;
use App\Http\Actions\Action;

class GetSkillForItem extends Action
{
    public function do($request){

        $character = Character::find($request->char_id);
        $exist = Skills::where('char_id', $character->id)->where('item_id', $request->item_id)->get();

        if(!count($exist)){
            $player_skills = Skills::where('char_id',  $character->id)
            ->where('level', '!=', 0)
            ->pluck('skill_name')
            ->toArray();

            $exist = SkillList::whereNotIn('skill_name', $player_skills)
                ->where('fp_req', '<=', $character->fight_potential)
                ->where('sp_req', '<=', $character->sorcery_potential)
                ->where('tp_req', '<=', $character->trick_potential)
                ->inRandomOrder()
                ->limit(3)
                ->get();

            foreach ($exist as $item){
                Skills::create([
                    'char_id' => $character->id,
                    'item_id' => $request->item_id,
                    'skill_name' => $item['skill_name'],
                    'skill_type' => $item['skill_type'],
                    'potential_increase' => $item['potential_increase'],
                ]);
            }

            $exist = Skills::where('char_id', $character->id)
            ->where('item_id', $request->item_id)
            ->get();
        }

        $this->addData(['skills' => $exist]);
        return $this->answer;
    }
}
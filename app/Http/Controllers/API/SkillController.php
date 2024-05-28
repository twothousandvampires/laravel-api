<?php

namespace App\Http\Controllers\API;

use App\Models\Character;
use App\Models\GemSkillList;
use App\Models\GemSkills;
use App\Models\SkillProperty;
use Illuminate\Http\Request;

class SkillController extends BaseController
{
    public function getList(): \Illuminate\Database\Eloquent\Collection
    {
        return GemSkillList::all();
    }
    public function upAmplification($id){

        $amp = SkillProperty::find($id);

        $amp->level ++;
        $amp->save();

        SkillProperty::where('skill_id', $amp->skill_id)->where('level', 0)->delete();

        return [
            'success' => true,
            'data' => $amp
        ];
    }

    public function upSkill($id, Request $request): array
    {
        $skill = GemSkills::find($id);
        if($skill->level < GemSkills::MAX_SKILL_LEVEL){
            $skill->level ++;
            $skill->save();
            $character = Character::find($request->player_id);
            $character->exp -= $request->exp_cost;
            $character->save();
            return [
                'success' => true,
            ];
        }
        else{
            return [
                'success' => false,
                'msg' => 'max skill level'
            ];
        }
    }

    public function upgradeAmplification($id, Request $request,): array
    {
        $amp = SkillProperty::find($id);
        if($amp->level >= $amp->max_level){
            return [
                'success' => false,
                'data' => 'maximum level!'
            ];
        }
        $amp->level ++;
        $amp->save();

        $character = Character::find($request->player_id);
        $character->exp -= $request->exp_cost;
        $character->save();

        return [
            'success' => true,
            'data' => $amp
        ];
    }

}

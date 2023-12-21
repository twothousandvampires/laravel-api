<?php

namespace App\Http\Controllers\API;

use App\Models\Character;
use App\Models\GemSkills;
use App\Models\SkillProperty;
use Illuminate\Http\Request;


class SkillController extends BaseController
{
    public function upAmplification($id){

        $amp = SkillProperty::find($id);
        $amp->level ++;
        $amp->save();

        SkillProperty::where('skill_id', $amp->skill_id)->where('level',0)->delete();

        return [
            'success' => true,
            'data' => $amp
        ];
    }

    public function upSkill($id, Request $request){
        $skill = GemSkills::find($id);
        $skill->level ++;
        $skill->save();

        $character = Character::find($request->player_id);
        $character->exp -= $request->exp_cost;
        $character->save();

        return [
            'success' => true,
        ];
    }

    public function upgradeAmplification($id, Request $request,){
        $amp = SkillProperty::find($id);
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

<?php

namespace App\Http\Controllers\API;

use App\Http\Services\Log;
use App\Models\Character;
use App\Models\GemSkillList;
use App\Models\GemSkills;
use App\Models\Item;
use App\Models\SkillList;
use App\Models\SkillProperty;
use App\Models\Skills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SkillController extends BaseController
{
    public function getSkills($char_id, $item_id): \Illuminate\Http\JsonResponse
    {
        $exist = Skills::where('char_id', $char_id)->where('item_id', $item_id)->get();
        if(!count($exist)){
            $player_skills = Skills::where('char_id', $char_id)->where('level', '!=', 0)->pluck('skill_name')->toArray();
            $exist = SkillList::inRandomOrder()->whereNotIn('skill_name', $player_skills)->limit(3)->get();
            foreach ($exist as $item){
                Skills::create([
                    'char_id' => $char_id,
                    'item_id' => $item_id,
                    'skill_name' => $item['skill_name'],
                    'skill_type' => $item['skill_type']
                ]);
            }
            $exist = Skills::where('char_id', $char_id)->where('item_id', $item_id)->get();
        }

        return $this->sendResponse($exist);
    }

    public function learnSkill($char_id, Request $request): \Illuminate\Http\JsonResponse
    {
        $log = App::make(Log::class);
        if(!$request->skill_id){
            $player_skills = Skills::where('char_id', $char_id)->pluck('skill_name')->toArray();
            $skill = SkillList::inRandomOrder()->whereNotIn('skill_name', $player_skills)->first();
            Skills::create([
                'char_id' => $char_id,
                'item_id' => null,
                'skill_name' => $skill['skill_name'],
                'skill_type' => $skill['skill_type'],
                'level' => 1
            ]);
            $log->addToLog('we');
            $character = Character::find($char_id);
            return $this->sendResponse($character);
        }
        else{
            $skill = Skills::find($request->skill_id);
            $item = Item::find($skill->item_id);

            $skill->level ++;
            $skill->item_id = null;
            $skill->save();

            $character = Character::find($item->char_id);

            $item->delete();

            return $this->sendResponse($character);
        }

    }

    public function upgradeSkill($char_id, Request $request): \Illuminate\Http\JsonResponse|bool
    {
        if(!$request->skill_id){
            $skill = Skills::where('char_id', $char_id)->where('level', '!=', 0)->inRandomOrder()->first();
        }
        else{
            $skill = Skills::find($request->skill_id);
        }
        if($skill){
            $skill->level ++;
            $skill->save();
            $character = Character::find($skill->char_id);

            return $this->sendResponse($character);
        }

        return $this->sendError('no skill to upgrade');
    }
}

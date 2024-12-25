<?php

namespace App\Http\Controllers\API;

use App\Models\Character;
use App\Models\Item;
use App\Models\SkillList;
use App\Models\Skills;
use Illuminate\Http\Request;
use App\Models\UsedDetail;
use App\Http\Services\ItemService;

class SkillController extends BaseController
{
    public function getSkills($char_id, $item_id): \Illuminate\Http\JsonResponse
    {
        $character = Character::find($char_id);
        $exist = Skills::where('char_id', $char_id)->where('item_id', $item_id)->get();

        if(!count($exist)){
            $player_skills = Skills::where('char_id', $char_id)->where('level', '!=', 0)->pluck('skill_name')->toArray();
            $exist = SkillList::whereNotIn('skill_name', $player_skills)
                ->where('fp_req', '<=', $character->fight_potential)
                ->where('sp_req', '<=', $character->sorcery_potential)
                ->where('tp_req', '<=', $character->trick_potential)
                ->inRandomOrder()
                ->limit(3)
                ->get();

            foreach ($exist as $item){
                Skills::create([
                    'char_id' => $char_id,
                    'item_id' => $item_id,
                    'skill_name' => $item['skill_name'],
                    'skill_type' => $item['skill_type'],
                    'potential_increase' => $item['potential_increase'],
                ]);
            }
            $exist = Skills::where('char_id', $char_id)->where('item_id', $item_id)->get();
        }

        return $this->sendResponse($exist);
    }

    public function learnSkill($char_id, $used_id, Request $request, ItemService $itemService): \Illuminate\Http\JsonResponse
    {
        $character = Character::find($char_id);
       
        if(!$request->skill_id){
            $player_skills = Skills::where('char_id', $char_id)->pluck('skill_name')->toArray();
            $skill = SkillList::whereNotIn('skill_name', $player_skills)
                ->where('fp_req', '<=', $character->fight_potential)
                ->where('sp_req', '<=', $character->sorcery_potential)
                ->where('tp_req', '<=', $character->trick_potential)
                ->inRandomOrder()
                ->first();

            Skills::create([
                'char_id' => $char_id,
                'item_id' => null,
                'skill_name' => $skill['skill_name'],
                'skill_type' => $skill['skill_type'],
                'potential_increase' => $skill['potential_increase'],
                'level' => 1
            ]);

            if($skill->potential_increase != null){
                $character[$skill->potential_increase] += 1;
            }
        }
        else{
            $skill = Skills::find($request->skill_id);
        
            $skill->level ++;
            $skill->item_id = null;
            $skill->save();

            if($skill->potential_increase != null){
                $character[$skill->potential_increase] += 1;
            }
        }

        $character->save();
        $used = Item::find($used_id);

        $itemService->useUsed($used);
        return $this->sendResponse($character);
    }

    public function upgradeSkill($char_id, $used_id, Request $request, ItemService $itemService): \Illuminate\Http\JsonResponse|bool
    {
        $used = Item::find($used_id);
        $itemService->useUsed($used);

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

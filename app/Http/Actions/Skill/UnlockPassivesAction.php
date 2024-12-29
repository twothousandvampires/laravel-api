<?php 
namespace App\Http\Actions\Skill;

use App\Models\Character;
use App\Models\Passives;
use App\Models\PassivesList;
use App\Http\Actions\Action;

class UnlockPassivesAction extends Action
{
    public function do($request){

        $character = Character::find($request->char_id);
        $passives = Passives::where('char_id', $character->id)->pluck('name')->toArray();
      
        $cost = count($passives) * 200 + 200;

        if($character->exp < $cost){
            $this->setUnsuccess('not enough exp!(need ' . $cost . ')');
            return $this->answer;
        }

        $character->exp -= $cost;
        $character->save();

        $new = PassivesList::inRandomOrder()
            ->where('fp_req', '<=', $character->fight_potential)
            ->where('sp_req', '<=', $character->sorcery_potential)
            ->where('tp_req', '<=', $character->trick_potential)
            ->whereNotIn('name', $passives)
            ->limit(3)
            ->get();

        foreach ($new as $item){
            Passives::create([
                'char_id' => $request->char_id,
                'name' => $item->name,
                'exp_cost' => $item->exp_cost,
                'potential_increase' => $item->potential_increase,
            ]);
        }

        $this->addData(['passives' => Passives::with('stats')
        ->where('char_id', $request->char_id)
        ->where('level', 0)
        ->get()]
        );
       
        return $this->answer;
    }
}
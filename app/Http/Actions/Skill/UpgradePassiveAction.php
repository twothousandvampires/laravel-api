<?php 
namespace App\Http\Actions\Skill;

use App\Models\Character;
use App\Models\Passives;
use App\Http\Actions\Action;
use App\Http\Services\PassiveService;

class UpgradePassiveAction extends Action
{
    public $requared_params = [
        'passive_id'
    ];

    public function do($request){

        $character = Character::find($request->char_id);
        $passive = Passives::with('stats')->find($request->passive_id);

        $cost = $passive->level * $passive->exp_cost;
        
        if($character->exp < $cost){
            $this->setUnsuccess('not enough exp!(need ' . $cost . ')');
            return $this->answer;
        }
        $passiveService = new PassiveService();
        $character->exp -= $cost;
        $passiveService->upgradePassive($character, $passive);
        $character->save();

        $this->addData(['char' => $character]);

        return $this->answer;
    }
}
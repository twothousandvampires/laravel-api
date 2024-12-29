<?php 
namespace App\Http\Actions\Skill;

use App\Models\Character;
use App\Models\Passives;
use App\Http\Actions\Action;
use App\Http\Services\PassiveService;

class LearnPassiveAction extends Action
{
    public $requared_params = [
        'passive_id'
    ];

    public function do($request){

        $passive = Passives::with('stats')->find($request->passive_id);

        if($passive){
            $passive->level = 1;
            $passive->save();
            $character = Character::find($request->char_id);

            $passiveService = new PassiveService();
            $passiveService->affect($passive, $character);
            
            $character->save();

            Passives::where('char_id', $character->id)->where('level', 0)->delete();
            $this->addData(['char' => $character]);
        }
        else{
            $this->setUnsuccess('passive not found');
        }

        return $this->answer;
    }
}
<?php
namespace App\Http\Services;

use App\Http\Services\Skill\Passive\Combat\Acrobatics\Acrobatics;
use App\Http\Services\Skill\Passive\Combat\Armored\Armored;
use App\Http\Services\Skill\Passive\Combat\BattleTechniques\BattleTechniques;
use App\Http\Services\Skill\Passive\Sorcery\DesctructionImpact\DestructionImpact;
use App\Http\Services\Skill\Passive\Sorcery\EternalFocus\EternalFocus;


class SkillTree{

    public function __construct()
    {
        $this->armored = new Armored();
        $this->acrobatics = new Acrobatics();
        $this->battle_techniques = new BattleTechniques();
        $this->destraction_impact = new DestructionImpact();
        $this->eternal_focus = new EternalFocus();
    }
}

<?php
namespace App\Http\Services;

use App\Http\Services\Skill\combat\Armored\Armored;
use App\Http\Services\Skill\combat\DesctructionImpact\DestructionImpact;
use App\Http\Services\Skill\Versality;


class SkillTree{

    public function __construct()
    {
        $this->stone_skin = new Armored();
        $this->versality = new Versality();
        $this->destruction_impact = new DestructionImpact();
    }
}

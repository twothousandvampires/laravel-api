<?php
namespace App\Http\Services;

use App\Http\Services\Skill\DestructionImpact;
use App\Http\Services\Skill\StoneSkin;
use App\Http\Services\Skill\Versality;


class SkillTree{

    public function __construct()
    {
        $this->stone_skin = new StoneSkin();
        $this->versality = new Versality();
        $this->destruction_impact = new DestructionImpact();
    }
}

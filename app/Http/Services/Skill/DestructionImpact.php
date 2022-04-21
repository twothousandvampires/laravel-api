<?php
namespace App\Http\Services\Skill;

class DestructionImpact{
    public function __construct()
    {
        $this->description = 'Increased AOE for spells, but reduced cast speed';
        $this->name = 'destruction impact';
        $this->level = 0;
        $this->affect = ['increased_spell_aoe', 'reduced_cast_speed'];
        $this->value = [3 , 2];
        $this->img_path = './src/assets/img/icons/skill/destruction_impact.png';
        $this->class = 'sorcery';
        $this->type = 'passive';
    }
}

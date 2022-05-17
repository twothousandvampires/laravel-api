<?php

namespace App\Http\Services\Skill\Passive\Sorcery\DesctructionImpact;

class CriticalMass
{
    public function __construct()
    {
        $this->description = 'reduce spell damage but increase critical chance';
        $this->name = 'Critical Mass';
        $this->level = 0;
        $this->affect = ['reduced_spell_damage','increased_spell_crit_chance'];
        $this->value = [10,20];
        $this->img_path = './src/assets/img/icons/skill/critical_mass.png';
        $this->class = 'combat';
        $this->type = 'passive';
        $this->requirements = [
            'level' => 0,
        ];
    }
}

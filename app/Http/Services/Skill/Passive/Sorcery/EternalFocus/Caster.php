<?php

namespace App\Http\Services\Skill\Sorcery\EternalFocus;

class Caster
{
    public function __construct()
    {
        $this->description = 'Increase cast speed but reduce spell crit chance';
        $this->name = 'Caster';
        $this->level = 0;
        $this->affect = ['increased_cast_speed', 'reduce_spell_crit_chance'];
        $this->value = [3,10];
        $this->img_path = './src/assets/img/icons/skill/caster.png';
        $this->class = 'sorcery';
        $this->type = 'passive';
        $this->requirements = [
            'level' => 0
        ];
    }
}

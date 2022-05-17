<?php

namespace App\Http\Services\Skill\Passive\Sorcery\EternalFocus;


class EternalFocus
{
    public function __construct()
    {
        $this->description = 'Increase spell damage';
        $this->name = 'Eternal focus';
        $this->level = 0;
        $this->affect = ['increased_spell_damage'];
        $this->value = [3];
        $this->img_path = './src/assets/img/icons/skill/eternal_focus.png';
        $this->class = 'sorcery';
        $this->type = 'passive';
        $this->requirements = [
            'level' => 0
        ];
        $this->childs = [
            new Caster()
        ];
    }
}

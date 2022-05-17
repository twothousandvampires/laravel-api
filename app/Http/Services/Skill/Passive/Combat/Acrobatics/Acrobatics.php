<?php

namespace App\Http\Services\Skill\Passive\Combat\Acrobatics;


class Acrobatics
{
    public function __construct()
    {
        $this->description = 'Increas evade chance';
        $this->name = 'Acrobatics';
        $this->level = 0;
        $this->affect = ['increased_evade_chance'];
        $this->value = [4 , 2];
        $this->img_path = './src/assets/img/icons/skill/acrobatics.png';
        $this->class = 'combat';
        $this->type = 'passive';
        $this->requirements = [
            'level' => 0
        ];
        $this->childs = [

        ];
    }
}

<?php
namespace App\Http\Services\Skill;
use App\Http\Services\Skill\Child\IronWill;



class Armored{
    public function __construct()
    {
        $this->description = 'Increas armour, but reduce movement speed';
        $this->name = 'Armored';
        $this->level = 0;
        $this->affect = ['increased_armour', 'reduced_movement_speed'];
        $this->value = [4 , 2];
        $this->img_path = './src/assets/img/icons/skill/armored.png';
        $this->class = 'combat';
        $this->type = 'passive';
        $this->requirements = [
            'level' => 0
        ];
        $this->childs = [
            new IronWill()
        ];
    }
}

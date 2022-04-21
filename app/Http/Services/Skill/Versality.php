<?php
namespace App\Http\Services\Skill;

class Versality{
    public function __construct()
    {
        $this->description = 'Increased attack and cast speed';
        $this->name = 'versality';
        $this->level = 0;
        $this->affect = ['increased_attack_speed', 'increased_cast_speed'];
        $this->value = [1 , 1];
        $this->img_path = './src/assets/img/icons/skill/versality.png';
        $this->class = 'combat';
        $this->type = 'passive';
    }
}

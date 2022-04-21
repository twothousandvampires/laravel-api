<?php
namespace App\Http\Services\Skill;

class StoneSkin{
    public function __construct()
    {
        $this->description = 'Increased armour, but reduced movement speed';
        $this->name = 'stone skin';
        $this->level = 0;
        $this->affect = ['increased_armour', 'reduced_movement_speed'];
        $this->value = [4 , 2];
        $this->img_path = './src/assets/img/icons/skill/stone_skin.png';
        $this->class = 'combat';
        $this->type = 'passive';
    }
}

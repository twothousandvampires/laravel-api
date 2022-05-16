<?php
namespace App\Http\Services\Skill\combat\Armored;

class IronWill
{
    public function __construct()
    {
        $this->description = 'Give you a chance to defend with a double resist';
        $this->name = 'Iron will';
        $this->level = 0;
        $this->affect = ['double_resist'];
        $this->value = [10];
        $this->img_path = './src/assets/img/icons/skill/iron_will.png';
        $this->class = 'Combat';
        $this->type = 'passive';
        $this->requirements = [
            'level' => 0,
            'passives' => [
                'will' => [4,6,8,10]
            ]
        ];
    }
}

<?php


namespace App\Http\Services\Skill\Passive\Combat\BattleTechniques;


class BattleWizard
{
    public function __construct()
    {
        $this->description = 'Give you a chance to increase cast speed for a time';
        $this->name = 'Battle wizard';
        $this->level = 0;
        $this->affect = ['increase_cast_speed'];
        $this->affect_type = 'trigger';
        $this->trigger_chance = 20;
        $this->trigger_type = 'on_hit';
        $this->value = [2];
        $this->img_path = './src/assets/img/icons/skill/iron_will.png';
        $this->class = 'combat';
        $this->type = 'passive';
        $this->requirements = [
            'level' => 0,
            'passives' => [
                'eternal focus' => [6,12,14,20]
            ]
        ];
    }
}

<?php
namespace App\Http\Services\Skill\Passive\Combat\BattleTechniques;

class BattleTechniques{
    public function __construct()
    {
        $this->description = 'Increased attack and amount of blocked damage';
        $this->name = 'Battle techniques';
        $this->level = 0;
        $this->affect = ['increased_attack_speed', 'increased_amount_block'];
        $this->value = [1 , 1];
        $this->img_path = './src/assets/img/icons/skill/battle_techniques.png';
        $this->class = 'Combat';
        $this->type = 'passive';
        $this->requirements = [
            'level' => 0
        ];
        $this->childs = [
            new BattleWizard()
        ];
    }
}

<?php


namespace App\Http\Services\Skill\Active;


class FireBall
{
    public function __construct()
    {
        $this->description = 'Fires the ball of fire';
        $this->name = 'Fire Ball';
        $this->level = 0;
        $this->img_path = './src/assets/img/icons/skill/fire_ball.png';
        $this->class = 'Sorcery';
        $this->type = 'active';
        $this->requirements = [
            'level' => 1
        ];
        $this->childs = [

        ];
    }
}

<?php


namespace App\Http\Services;
use App\Http\Services\Skill\Active\FireBall;

class SkillService
{

    public function create($name){
        $className = 'App\\Http\\Services\\Skill\\Active\\' . $name;

        return new $className;
    }

}

<?php

namespace App\Http\Services;

class PassiveService
{
    public function affect($passive, &$character): void
    {
        if($passive->potential_increase != null){
            $character[$passive->potential_increase] += 1;
        }

        foreach($passive->stats as $stat){
            if($stat != null){
                if($passive->level == 1){
                    $character[$stat->stat] += $stat->start_value;
                }
                else{
                    $character[$stat->stat] -= floor(($passive->level - 2) * $stat->add_per_level);
                    $character[$stat->stat] += floor(($passive->level - 1) * $stat->add_per_level);
                }
            }
        }
    }

    public function upgradePassive(&$character, $passive): void
    {
        $passive->level ++;
        $passive->save();

        $this->affect($passive, $character);
    }
}

<?php

namespace App\Http\Services;

class PassiveService
{
    public function affect($passive, &$character): void
    {
        if($passive->stat != null){
            $prev_level = $passive->level - 1;
            $character[$passive->stat] -= $prev_level * $passive->add_per_level;
            $character[$passive->stat] += $passive->level * $passive->add_per_level;
            $character->save();
        }
    }
}

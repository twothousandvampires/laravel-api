<?php

namespace App\Http\Services;

class EquipPropertyService
{

    static function affectToCharacter(&$prop, &$character, $penalty, $inc_effect): void
    {
        if($prop->sub_type === null){
            return;
        }
        $value =  $prop->value;
        $value = floor($value * (1 - ($penalty / 100)));

        if($prop->sub_type === 1){
            $value = floor($value * (1 + $inc_effect / 100));
            $character[$prop->stat] += $value;
        }
        else if($prop->sub_type === 2){
            $character[$prop->stat] -= $value;
        }

    }

    static function unaffectToCharacter($prop, &$character, $penalty, $inc_effect): void
    {
        if($prop->sub_type === null){
            return;
        }
        $value =  $prop->value;
        $value = floor($value * (1 - ($penalty / 100)));

        if($prop->sub_type === 1){
            $value = floor($value * (1 + $inc_effect / 100));
            $character[$prop->stat] -= $value;

        }
        else if($prop->sub_type === 2){
            $character[$prop->stat] += $value;
        }

    }
    static function affectBonusToCharacter(&$prop, &$character): void
    {
        if($prop->sub_type === null){
            return;
        }
        $value =  $prop->value;
        $value = floor($value * (20 / 100));

        $character[$prop->stat] += $value;
    }

    static function unaffectBonusToCharacter(&$prop, &$character): void
    {
        if($prop->sub_type === null){
            return;
        }
        $value =  $prop->value;
        $value = floor($value * (20 / 100));

        if($prop->sub_type === 1)
        {
            $character[$prop->stat] -= $value;
        }
        else if($prop->sub_type === 2)
        {
            $character[$prop->stat] -= $value;
        }
    }
}

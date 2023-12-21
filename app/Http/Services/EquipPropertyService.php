<?php

namespace App\Http\Services;

class EquipPropertyService
{

    static function affectToCharacter(&$prop, &$character, $penalty): void
    {
        if($prop->prop_type === 1){
            $value =  $prop->value;
            $value = floor($value - ($value * ($penalty / 100)));

            if($prop->sub_type === 1){
                $character[$prop->stat] += $value;
            }
            else if($prop->sub_type === 2){
                $character[$prop->stat] -= $value;
            }
        }
    }

    static function unaffectToCharacter($prop, &$character, $penalty, $row, $column): void
    {
        if($prop->prop_type === 1){
            $value =  $prop->value;
            $row_value = floor($value * ($row  / 100));
            $column_value = floor($value * ( $column / 100));
            $value = floor($value - ($value * ($penalty / 100)));

            if($prop->sub_type === 1){
                if($prop->inc_type === 1)
                {
                    $character[$prop->stat] -= $row_value + $column_value + $value;
                }
                elseif ($prop->inc_type === 2)
                {
                    $character[$prop->stat] -= $value - $row_value - $column_value;
                }
            }
            else if($prop->sub_type === 2){
                if($prop->inc_type === 1)
                {
                    $character[$prop->stat] += $value - $row_value - $column_value;
                }
                elseif ($prop->inc_type === 2)
                {
                    $character[$prop->stat] +=  $value + $row_value + $column_value;
                }
            }
        }
    }
    static function affectBonusToCharacter(&$prop, &$character): void
    {
        if($prop->prop_type === 1){
            $value =  $prop->value;
            $value = floor($value * (10 / 100));
            if($prop->sub_type === 1)
            {
                if($prop->inc_type === 1){
                    $character[$prop->stat] += $value;
                }
                else if($prop->inc_type === 2){
                    $character[$prop->stat] -= $value;
                }
            }
            else if($prop->sub_type === 2)
            {
                if($prop->inc_type === 1){
                    $character[$prop->stat] += $value;
                }
                else if($prop->inc_type === 2){
                    $character[$prop->stat] -= $value;
                }
            }
        }
    }

    static function unaffectBonusToCharacter(&$prop, &$character): void
    {
        if($prop->prop_type === 1){
            $value =  $prop->value;
            $value = floor($value * (10 / 100));
            if($prop->sub_type === 1)
            {
                if($prop->inc_type === 1){
                    $character[$prop->stat] -= $value;
                }
                else if($prop->inc_type === 2){
                    $character[$prop->stat] += $value;
                }
            }
            else if($prop->sub_type === 2)
            {
                if($prop->inc_type === 1){
                    $character[$prop->stat] -= $value;
                }
                else if($prop->inc_type === 2){
                    $character[$prop->stat] += $value;
                }
            }
        }
    }
}

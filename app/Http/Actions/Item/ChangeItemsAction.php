<?php 
namespace App\Http\Actions\Item;

use App\Models\Character;
use App\Models\Item;
use App\Models\EquipDetail;
use App\Http\Services\EquipPropertyService;
use App\Http\Actions\Action;

class ChangeItemsAction extends Action
{

    public function do($request){
        $character = Character::find($request->char_id);

        $from = Item::find($request->moved);
        $to = Item::where('char_id', $character->id)->where('slot', $request->target)->first();

        if ($to) {
            $temp_slot = $from->slot;

            if ($from->isEquipped()) {
                $this->unequip($from, $character);
            }

            $from->slot = $to->slot;

            if ($from->isEquipped()) {
                $this->equip($from, $character);
            }

            $from->save();

            if ($to->isEquipped()) {
                $this->unequip($to, $character);
            }

            $to->slot = $temp_slot;

            if ($to->isEquipped()) {
                $this->equip($to, $character);
            }

            $to->save();

        } else {
            if ($from->isEquipped()) {
                $this->unequip($from, $character);
            }

            $from->slot = $request->target;

            if ($from->isEquipped()) {
                $this->equip($from, $character);
            }

            $from->save();
        }

        $all = Item::where('char_id', $character->id)
            ->whereBetween('slot', [0, 8])
            ->get();

        $rows = $this->checkRowsAndColumns($all);

        foreach ($all as $item) {
                if ($rows['combat'] == 3) {
                    if ($item->details->equip_class == 1 && $item->slot < 3 && !$item->details->row_bonus) {
                        foreach ($item->props as $prop) {
                            EquipPropertyService::affectBonusToCharacter($prop, $character);
                        }
                        EquipDetail::where('item_id', $item->id)->update([
                            'row_bonus' => 1,
                        ]);
                    }
                }
                if ($rows['sorcery'] == 3) {
                    if ($item->details->equip_class == 2 && $item->slot > 2 && $item->slot < 6 && !$item->details->row_bonus) {
                        foreach ($item->props as $prop) {
                            EquipPropertyService::affectBonusToCharacter($prop, $character);
                        }
                        EquipDetail::where('item_id', $item->id)->update([
                            'row_bonus' => 1,
                        ]);
                    }
                }
                if ($rows['movement'] == 3) {
                    if ($item->details->equip_class == 3 && $item->slot > 5 && $item->slot < 9 && !$item->details->row_bonus) {
                        foreach ($item->props as $prop) {
                            EquipPropertyService::affectBonusToCharacter($prop, $character);
                        }
                        EquipDetail::where('item_id', $item->id)->update([
                            'row_bonus' => 1,
                        ]);
                    }
                }

                if ($rows['weapon'] == 3) {
                    if ($item->details->equip_type == 1 && in_array($item->slot, [0, 3, 6]) && !$item->details->column_bonus) {
                        foreach ($item->props as $prop) {
                            EquipPropertyService::affectBonusToCharacter($prop, $character);
                        }
                        EquipDetail::where('item_id', $item->id)->update([
                            'column_bonus' => 1,
                        ]);
                    }
                }

                if ($rows['armour'] == 3) {
                    if ($item->details->equip_type == 2 && in_array($item->slot, [1, 4, 7]) && !$item->details->column_bonus) {
                        foreach ($item->props as $prop) {
                            EquipPropertyService::affectBonusToCharacter($prop, $character);
                        }
                        EquipDetail::where('item_id', $item->id)->update([
                            'column_bonus' => 1,
                        ]);
                    }
                }

                if ($rows['accessory'] == 3) {
                    if ($item->details->equip_type == 3 && in_array($item->slot, [2, 5, 8]) && !$item->details->column_bonus) {
                        foreach ($item->props as $prop) {
                            EquipPropertyService::affectBonusToCharacter($prop, $character);
                        }
                        EquipDetail::where('item_id', $item->id)->update([
                            'column_bonus' => 1,
                        ]);
                    }
                }
            }
            if ($character->life > $character->max_life) {
                $character->life = $character->max_life;
            }
            if ($character->mana > $character->max_mana) {
                $character->mana = $character->max_mana;
            }

    
            $character->save();
            $this->addData(['char' => $character]);
        
    
        return $this->answer;
    }

    private function unequip(&$item, &$character)
    {
        $penalty = $this->checkSlotPenalty($item);
        $inc_effect= $item->details->inc_effect;

        foreach ($item->props as $prop) {
            EquipPropertyService::unaffectToCharacter($prop, $character, $penalty, $inc_effect);
        }

        EquipDetail::where('item_id', $item->id)->update([
                'penalty' => 0
        ]);

        $details = EquipDetail::where('item_id', $item->id)->first();

        if($details->row_bonus == 1){
            $row_items = Item::leftJoin('equip_details','items.id','=','equip_details.item_id')->where('equip_details.row_bonus', 1)->get(['items.id']);
            foreach ($row_items as $sub_item){
                $props = Property::where('item_id', $sub_item->id)->get();
                foreach ($props as $prop) {
                    EquipPropertyService::unaffectBonusToCharacter($prop, $character);
                }
                EquipDetail::where('item_id', $sub_item->id)->update([
                    'row_bonus' => 0
                ]);
            }
        }

        if($details->column_bonus == 1){
            $column_items = Item::leftJoin('equip_details','items.id','=','equip_details.item_id')->where('equip_details.column_bonus', 1)->get(['items.id']);
            foreach ($column_items as $sub_item){
                $props = Property::where('item_id', $sub_item->id)->get();
                foreach ($props as $prop) {
                    EquipPropertyService::unaffectBonusToCharacter($prop, $character);
                }
                EquipDetail::where('item_id', $sub_item->id)->update([
                    'column_bonus' => 0
                ]);
            }
        }

    }

    private function equip(&$item, &$character)
    {
        $penalty = $this->checkSlotPenalty($item);
        $inc_effect= $item->details->inc_effect;

        EquipDetail::where('item_id', $item->id)->update([
            'penalty' => $penalty
        ]);

        foreach ($item->props as $prop) {
            EquipPropertyService::affectToCharacter($prop, $character, $penalty, $inc_effect);
        }
    }

    private function checkSlotPenalty($item): int
    {

        $penalty = 0;

        switch ($item->slot) {
            case 0:
                if ($item->details->equip_class != 1 && $item->details->equip_type != 1) {
                    $penalty = 75;
                } else if ($item->details->equip_class != 1 || $item->details->equip_type != 1) {
                    $penalty = 50;
                }
                break;
            case 1:
                if ($item->details->equip_class != 1 && $item->details->equip_type != 2) {
                    $penalty = 75;
                } else if ($item->details->equip_class != 1 || $item->details->equip_type != 2) {
                    $penalty = 50;
                }
                break;
            case 2:
                if ($item->details->equip_class != 1 && $item->details->equip_type != 3) {
                    $penalty = 75;
                } else if ($item->details->equip_class != 1 || $item->details->equip_type != 3) {
                    $penalty = 50;
                }
                break;
            case 3:
                if ($item->details->equip_class != 2 && $item->details->equip_type != 1) {
                    $penalty = 75;
                } else if ($item->details->equip_class != 2 || $item->details->equip_type != 1) {
                    $penalty = 50;
                }
                break;
            case 4:
                if ($item->details->equip_class != 2 && $item->details->equip_type != 2) {
                    $penalty = 75;
                } else if ($item->details->equip_class != 2 || $item->details->equip_type != 2) {
                    $penalty = 50;
                }
                break;
            case 5:
                if ($item->details->equip_class != 2 && $item->details->equip_type != 3) {
                    $penalty = 75;
                } else if ($item->details->equip_class != 2 || $item->details->equip_type != 3) {
                    $penalty = 50;
                }
                break;
            case 6:
                if ($item->details->equip_class != 3 && $item->details->equip_type != 1) {
                    $penalty = 75;
                } else if ($item->details->equip_class != 3 || $item->details->equip_type != 1) {
                    $penalty = 50;
                }
                break;
            case 7:
                if ($item->details->equip_class != 3 && $item->details->equip_type != 2) {
                    $penalty = 75;
                } else if ($item->details->equip_class != 3 || $item->details->equip_type != 2) {
                    $penalty = 50;
                }
                break;
            case 8:
                if ($item->details->equip_class != 3 && $item->details->equip_type != 3) {
                    $penalty = 75;
                } else if ($item->details->equip_class != 3 || $item->details->equip_type != 3) {
                    $penalty = 50;
                }
                break;

        }

        return $penalty;
    }

    private function checkRowsAndColumns($items): array
    {
        $result = [
            'combat' => 0,
            'sorcery' => 0,
            'movement' => 0,
            'weapon' => 0,
            'armour' => 0,
            'accessory' => 0
        ];

        foreach ($items as $item) {
            if ($item->details->equip_class === 1 && $item->slot < 3) {
                $result['combat']++;
            }
            if ($item->details->equip_class === 2 && $item->slot > 2 && $item->slot < 6) {
                $result['sorcery']++;
            }
            if ($item->details->equip_class === 3 && $item->slot > 5 && $item->slot < 9) {
                $result['movement']++;
            }

            if ($item->details->equip_type === 1 && in_array($item->slot, [0, 3, 6])) {
                $result['weapon']++;
            }
            if ($item->details->equip_type === 2 && in_array($item->slot, [1, 4, 7])) {
                $result['armour']++;
            }
            if ($item->details->equip_type === 3 && in_array($item->slot, [2, 5, 8])) {
                $result['accessory']++;
            }
        }

        return $result;
    }
}
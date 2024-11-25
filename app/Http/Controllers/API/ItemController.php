<?php

namespace App\Http\Controllers\API;

use App\Http\Services\EquipPropertyService;
use App\Http\Services\ItemService;
use App\Models\Character;
use App\Models\EquipDetail;
use App\Models\EquipPropertiesList;
use App\Models\Item;
use App\Models\ItemsList;
use App\Models\Passives;
use App\Models\Property;
use Illuminate\Http\Request;

class ItemController extends BaseController
{

    public function upgradeQuality($char_id, $item_id): \Illuminate\Http\JsonResponse
    {
        $item = Item::find($item_id);

        $prev_q = $item->details->equip_quality;
        $new_q = $prev_q + 1;

        if($new_q > 4){
            $new_q = 4;
        }

        EquipDetail::where('item_id', $item_id)->update([
            'equip_quality' => $new_q
        ]);

        $props = Property::where('item_id', $item_id)->get();

        foreach ($props as $prop){
            $value = EquipPropertiesList::where('id', $prop->prop_list_id)->select(ITEM::QUALITY[$new_q])->first();
            $prop->value = $value[ITEM::QUALITY[$new_q]];
            $prop->save();
        }

        $item->fresh();

        return $this->sendResponse(['items' => Item::where('char_id', $char_id)->get()]);
    }
    public function upgradeEffect($char_id, $item_id): \Illuminate\Http\JsonResponse
    {
        $item = Item::find($item_id);

        $details = EquipDetail::where('item_id', $item_id)->first();
        $effect = mt_rand(2, 8);
        $details->inc_effect += $effect;
        $details->save();

        $item->fresh();

        return $this->sendResponse(['items' => Item::where('char_id', $char_id)->get()]);
    }
    public function addProperty($char_id, $item_id, Request $request){
        $item = Item::find($item_id);
        $details = EquipDetail::where('item_id', $item->id)->first();
        switch ($request->prop_type){
            case 'all':
                $prop = EquipPropertiesList::select('id', ITEM::QUALITY[$details->equip_quality], 'stat' , 'name', 'sub_type')->inRandomOrder()->first();
                Property::create([
                   'name' => $prop->name,
                   'item_id' => $item_id,
                   'stat' => $prop->stat,
                    'sub_type' => $prop->sub_type,
                    'value' => $prop[ITEM::QUALITY[$details->equip_quality]],
                    'prop_list_id' => $prop->id
                ]);
                return $this->sendResponse(['items' => Item::where('char_id', $char_id)->get()]);
        }
    }
    public function unequip(&$item, &$character)
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

    public function equip(&$item, &$character)
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

    public function checkSlotPenalty($item): int
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

    public function checkRowsAndColumns($items): array
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

    public function change($char_id, Request $request): \Illuminate\Http\JsonResponse|string
    {

        $character = Character::find($char_id);

        if (!$character) {
            return $this->sendError('character not found');
        }

        $from = Item::where('slot', $request->from)->first();
        $to = Item::where('slot', $request->to)->first();

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

            $from->slot = $request->to;

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
            return $this->sendResponse(['data' => $character], 'Successfully.');

    }

    public function getList(): \Illuminate\Database\Eloquent\Collection
    {
        return ItemsList::all();
    }

    public function create(Request $request, ItemService $itemService): \Illuminate\Http\JsonResponse
    {
        if ($request->item_name) {
            $item = $itemService->createByName($request->item_name, $request->char_id);
        } else {
            $item = $itemService->createRandomItem($request->char_id);
        }
        return $this->sendResponse(['item' => $item], 'Successfully.');

    }

    public function delete(Request $request): \Illuminate\Http\JsonResponse
    {

        $item = Item::find($request->id);
        if($item){
            $item->delete();
        }


        return $this->sendResponse([], 'Successfully.');
    }

    public function deleteAll(Request $request): \Illuminate\Http\JsonResponse
    {
        Item::where('char_id', $request->char_id)->delete();
        return $this->sendResponse('Successfully.');

    }
}

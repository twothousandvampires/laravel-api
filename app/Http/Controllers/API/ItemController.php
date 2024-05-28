<?php
namespace App\Http\Controllers\API;

use App\Http\Services\CharacterService;
use App\Http\Services\EquipPropertyService;
use App\Http\Services\InventoryService;
use App\Http\Services\ItemService;
use App\Http\Services\NodeService;
use App\Models\Character;
use App\Models\EquipDetail;
use App\Models\GemSkills;
use App\Models\Item;
use App\Models\ItemsList;
use App\Models\Node;
use App\Models\SkillProperty;
use App\Models\SkillPropertyList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends BaseController
{
    public function gemUnequip(&$item, &$character){
        //todo gem quality
        GemSkills::unaffectPassiveSkillToCharacter($item, $character);
    }

    public function gemEquip(&$item, &$character){
        //todo gem quality
        GemSkills::affectPassiveSkillToCharacter($item, $character);
    }

    public function unequip(&$item, &$character){

        $penalty = $this->checkSlotPenalty($item);
        EquipDetail::where('item_id', $item->id)->update([
            'penalty' => 0
        ]);
        $row = $item->details->row_bonus ? 10 : 0;
        $column = $item->details->column_bonus ? 10 : 0;

        foreach ($item->props as $prop){
            EquipPropertyService::unaffectToCharacter($prop, $character, $penalty, $row, $column);
        }
        if($row || $column){
            EquipDetail::where('item_id', $item->id)->update([
                'row_bonus' => 0,
                'column_bonus' => 0,
            ]);
        }
    }
    public function equip(&$item, &$character){

        $penalty = $this->checkSlotPenalty($item);

        EquipDetail::where('item_id', $item->id)->update([
            'penalty' => $penalty
        ]);
        foreach ($item->props as $prop){
            EquipPropertyService::affectToCharacter($prop, $character, $penalty);
        }

    }
    public function checkSlotPenalty($item){

        $penalty = 0;

        switch ($item->slot){
            case 0:
                if($item->details->equip_class != 1 && $item->details->equip_type != 1){
                    $penalty = 75;
                }
                else if($item->details->equip_class != 1 || $item->details->equip_type != 1){
                    $penalty = 50;
                }
            break;
            case 1:
                if($item->details->equip_class != 1 && $item->details->equip_type != 2){
                    $penalty = 75;
                }
                else if($item->details->equip_class != 1 || $item->details->equip_type != 2){
                    $penalty = 50;
                }
                break;
            case 2:
                if($item->details->equip_class != 1 && $item->details->equip_type != 3){
                    $penalty = 75;
                }
                else if($item->details->equip_class != 1 || $item->details->equip_type != 3){
                    $penalty = 50;
                }
                break;
            case 3:
                if($item->details->equip_class != 2 && $item->details->equip_type != 1){
                    $penalty = 75;
                }
                else if($item->details->equip_class != 2 || $item->details->equip_type != 1){
                    $penalty = 50;
                }
                break;
            case 4:
                if($item->details->equip_class != 2 && $item->details->equip_type != 2){
                    $penalty = 75;
                }
                else if($item->details->equip_class != 2 || $item->details->equip_type != 2){
                    $penalty = 50;
                }
                break;
            case 5:
                if($item->details->equip_class != 2 && $item->details->equip_type != 3){
                    $penalty = 75;
                }
                else if($item->details->equip_class != 2 || $item->details->equip_type != 3){
                    $penalty = 50;
                }
                break;
            case 6:
                if($item->details->equip_class != 3 && $item->details->equip_type != 1){
                    $penalty = 75;
                }
                else if($item->details->equip_class != 3 || $item->details->equip_type != 1){
                    $penalty = 50;
                }
                break;
            case 7:
                if($item->details->equip_class != 3 && $item->details->equip_type != 2){
                    $penalty = 75;
                }
                else if($item->details->equip_class != 3 || $item->details->equip_type != 2){
                    $penalty = 50;
                }
                break;
            case 8:
                if($item->details->equip_class != 3 && $item->details->equip_type != 3){
                    $penalty = 75;
                }
                else if($item->details->equip_class != 3 || $item->details->equip_type != 3){
                    $penalty = 50;
                }
                break;

        }

        return $penalty;
    }

    public function checkRowsAndColums($items){
        $result = [
            'combat' => 0,
            'sorcery' => 0,
            'movement' => 0,
            'weapon' => 0,
            'armour' => 0,
            'accessory' => 0
        ];

        foreach ($items as $item){
            if($item->details->equip_class === 1 && $item->slot < 3){
                $result['combat']++;
            }
            if($item->details->equip_class === 2 && $item->slot > 2 && $item->slot < 6){
                $result['sorcery']++;
            }
            if($item->details->equip_class === 3 && $item->slot > 5  && $item->slot < 9){
                $result['movement']++;
            }

            if($item->details->equip_type === 1 && in_array($item->slot, [0,3,6])){
                $result['weapon']++;
            }
            if($item->details->equip_type === 2 && in_array($item->slot, [1,4,7])){
                $result['armour']++;
            }
            if($item->details->equip_type === 3 && in_array($item->slot, [2,5,8])){
                $result['accessory']++;
            }
        }

        return $result;
    }

    public function change(Request $request){
        $from = Item::where('slot', $request->from)->first();
        $character = Character::find($from->char_id);
        $to = Item::where('slot', $request->to)->first();

        if(!$character){
            return 'char not found';
        }

        if($to){
            $temp_slot = $from->slot;

            if(Item::isEquipEquipped($from)){
               $this->unequip($from, $character);
            }
            else if(Item::isGemEquipped($from)){
                $this->gemUnequip($from, $character);
            }

            $from->slot = $to->slot;

            if(Item::isEquipEquipped($from)){
                $this->equip($from, $character);
            }
            else if(Item::isGemEquipped($from)){
                $this->gemEquip($from, $character);
            }

            $from->save();
            if(Item::isEquipEquipped($to)){
                $this->unequip($to, $character);
            }
            else if(Item::isGemEquipped($from)){
                $this->gemUnequip($to, $character);
            }

            $to->slot = $temp_slot;

            if(Item::isEquipEquipped($to)){
                $this->equip($to, $character);
            }
            else if(Item::isGemEquipped($from)){
                $this->gemEquip($to, $character);
            }

            $to->save();

        }
        else{
            if(Item::isEquipEquipped($from)){
                $this->unequip($from, $character);
            }
            if(Item::isGemEquipped($from)){
                $this->gemUnequip($from, $character);
            }


            $from->slot = $request->to;

            if(Item::isEquipEquipped($from)){
                 $this->equip($from, $character);
            }
            if(Item::isGemEquipped($from)){
                $this->gemEquip($from, $character);
            }

            $from->save();
        }

        $all = Item::where('char_id', $character->id)
            ->whereBetween('slot',[0, 8])
            ->get();

        $rows = $this->checkRowsAndColums($all);

        foreach ($all as $item){
            if($rows['combat'] == 3){
                if($item->details->equip_class == 1 && $item->slot < 3 && !$item->details->row_bonus){
                    foreach ($item->props as $prop){
                        EquipPropertyService::affectBonusToCharacter($prop, $character);
                    }
                    EquipDetail::where('item_id', $item->id)->update([
                        'row_bonus' => 1,
                    ]);
                }
            }
            else{
                if($item->details->equip_class == 1 && $item->slot < 3 && $item->details->row_bonus){
                    foreach ($item->props as $prop){
                        EquipPropertyService::unaffectBonusToCharacter($prop, $character);
                    }
                    EquipDetail::where('item_id', $item->id)->update([
                        'row_bonus' => 0,
                    ]);
                }
            }

            if($rows['sorcery'] == 3){
                if($item->details->equip_class == 2 && $item->slot > 2 && $item->slot < 6 && !$item->details->row_bonus){
                    foreach ($item->props as $prop){
                        EquipPropertyService::affectBonusToCharacter($prop, $character);
                    }
                    EquipDetail::where('item_id', $item->id)->update([
                        'row_bonus' => 1,
                    ]);
                }
            }
            else{
                if($item->details->equip_class == 2 && $item->slot > 2 && $item->slot < 6 && $item->details->row_bonus){
                    foreach ($item->props as $prop){
                        EquipPropertyService::unaffectBonusToCharacter($prop, $character);
                    }
                    EquipDetail::where('item_id', $item->id)->update([
                        'row_bonus' => 0,
                    ]);
                }
            }

            if($rows['movement'] == 3){
                if($item->details->equip_class == 3 && $item->slot > 5 && $item->slot < 9 && !$item->details->row_bonus){
                    foreach ($item->props as $prop){
                        EquipPropertyService::affectBonusToCharacter($prop, $character);
                    }
                    EquipDetail::where('item_id', $item->id)->update([
                        'row_bonus' => 1,
                    ]);
                }
            }
            else{
                if($item->details->equip_class == 3 && $item->slot > 5 && $item->slot < 9 && $item->details->row_bonus){
                    foreach ($item->props as $prop){
                        EquipPropertyService::unaffectBonusToCharacter($prop, $character);
                    }
                    EquipDetail::where('item_id', $item->id)->update([
                        'row_bonus' => 0,
                    ]);
                }
            }


            if($rows['weapon'] == 3){
                if($item->details->equip_type == 1 && in_array($item->slot, [0,3,6]) && !$item->details->column_bonus){
                    foreach ($item->props as $prop){
                        EquipPropertyService::affectBonusToCharacter($prop, $character);
                    }
                    EquipDetail::where('item_id', $item->id)->update([
                        'column_bonus' => 1,
                    ]);
                }
            }
            else{
                if($item->details->equip_type == 1 && in_array($item->slot, [0,3,6]) && $item->details->column_bonus){
                    foreach ($item->props as $prop){
                        EquipPropertyService::unaffectBonusToCharacter($prop, $character);
                    }
                    EquipDetail::where('item_id', $item->id)->update([
                        'column_bonus' => 0,
                    ]);
                }
            }

            if($rows['armour'] == 3){
                if($item->details->equip_type == 2 && in_array($item->slot, [1,4,7]) &&  !$item->details->column_bonus){
                    foreach ($item->props as $prop){
                        EquipPropertyService::affectBonusToCharacter($prop, $character);
                    }
                    EquipDetail::where('item_id', $item->id)->update([
                        'column_bonus' => 1,
                    ]);
                }
            }
            else{
                if($item->details->equip_type == 2 && in_array($item->slot, [1,4,7]) && $item->details->column_bonus){
                    foreach ($item->props as $prop){
                        EquipPropertyService::unaffectBonusToCharacter($prop, $character);
                    }
                    EquipDetail::where('item_id', $item->id)->update([
                        'column_bonus' => 0,
                    ]);
                }
            }

            if($rows['accessory'] == 3){
                if($item->details->equip_type == 3 && in_array($item->slot, [2,5,8]) && !$item->details->column_bonus){
                    foreach ($item->props as $prop){
                        EquipPropertyService::affectBonusToCharacter($prop, $character);
                    }
                    EquipDetail::where('item_id', $item->id)->update([
                        'column_bonus' => 1,
                    ]);
                }
            }
            else{
                if($item->details->equip_type == 3 && in_array($item->slot, [2,5,8]) && $item->details->column_bonus){
                    foreach ($item->props as $prop){
                        EquipPropertyService::unaffectBonusToCharacter($prop, $character);
                    }
                    EquipDetail::where('item_id', $item->id)->update([
                        'column_bonus' => 0,
                    ]);
                }
            }
        }

        $character->save();
        return $this->sendResponse(['data' => $character], 'Successfully.');
    }

    public function getList(){
        return ItemsList::all();
    }

    public function create(Request $request, ItemService $itemService){
        if($request->item_name){
            $item = $itemService->createByName($request->item_name, $request->char_id, $request->skill_name);
        }
        else{
            $item = $itemService->createRandomItem($request->char_id);
        }
        return $this->sendResponse(['item' => $item], 'Successfully.');

    }

    public function delete(Request $request){

        $item = Item::find($request->id);
        $item->delete();

        return $this->sendResponse([], 'Successfully.');
    }

    public function deleteAll(Request $request){
        Item::where('char_id', $request->char_id)->delete();
        return $this->sendResponse('Successfully.');

    }

    public function use($item_id, Request $request, ItemService $item_service){

        $item = Item::with('properties')->find($item_id);
        $character = Character::find($item->char_id);

        if($character->user_id === Auth::user()->id){

            $skill = $item_service->use($request, $item, $character);
            $type = $item->class;
            $item->delete();
            switch ($type){
                case 'book':
                    return $this->sendResponse(['data'=>$skill], 'Successfully.',);
            }
        }
    }

    public function amplifications(Request $request){

        $item_id = $request->item_id;

        $skill = GemSkills::where('item_id', $item_id)->first();
        $existed = SkillProperty::where('skill_id', $skill->id)->pluck('name');
        $character = Character::find($request->player_id);

        if($character->exp >= $request->exp_cost){

            $character->exp -= $request->exp_cost;
            $character->save();

            $base_props = SkillPropertyList::where('parent_name', $skill->name)
                ->inRandomOrder()
                ->where('parent_name', $skill->name)
                ->whereNotIn('name', $existed)
                ->limit(2)
                ->get();

            if(!count($base_props)){
                return [
                    'success' => false,
                    'msg' => 'no amplifications!'
                ];
            }

            $props = [];

            foreach ($base_props as $prop){
                $props[] = SkillProperty::create([
                    'skill_id' => $skill->id,
                    'name' => $prop->name,
                    'type' => $prop->type,
                    'exp_needed' => $prop->exp_needed,
                    'max_level' => $prop->max_level,
                    'level' => 0
                ]);
            }
            return [
                'success' => true,
                'data' => $props
            ];
        }

        else{
            return [
                'success' => true,
                'msg' => 'not enough experience!'
            ];
        }
    }

}

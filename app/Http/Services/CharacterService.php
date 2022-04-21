<?php

namespace App\Http\Services;
use App\Models\Character;
use App\Http\Services\InventoryService;
use App\Models\SkillTreeModel;

class CharacterService{

    function __construct(){
        $this->inventory_service = new InventoryService();
    }

    public function componateCharacter($char_id): array
    {
        $character = Character::find($char_id);
        $items = $this->inventory_service->createInventory($char_id);
        $skill_tree = SkillTreeModel::where('char_id',$char_id)->first()->body;

        return [
            'character' => $character,
            'items' => $items,
            'skill_tree' => $skill_tree,
        ];
    }

}

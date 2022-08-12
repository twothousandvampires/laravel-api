<?php

namespace App\Http\Services;
use App\Models\Character;
use App\Http\Services\InventoryService;
use App\Models\SkillTreeModel;

class CharacterService{

    function __construct(){
        $this->inventory_service = new InventoryService();
    }

    public function componateCharacter($char_id): object
    {
        return Character::with('items.properties', 'skills.properties', 'skills.chields.properties')->find($char_id);
    }
}

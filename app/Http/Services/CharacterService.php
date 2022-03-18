<?php

namespace App\Http\Services;
use App\Models\Character;
use App\Http\Services\InventoryService;

class CharacterService{

    function __construct(){
        $this->inventory_service = new InventoryService();
    }

    public function componateCharacter($char_id): array
    {
        $character = Character::find($char_id);
        $items = $this->inventory_service->createInventory($char_id);

        return [
            'character' => $character,
            'items' => $items,
        ];
    }

}

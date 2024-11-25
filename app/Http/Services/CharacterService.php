<?php

namespace App\Http\Services;
use App\Models\Character;
use App\Models\NodeStats;
use Illuminate\Support\Facades\Auth;

class CharacterService{

    public function createCharacter($req, NodeService $nodeService){
        $char = new Character();
        $char->name = $req->name;
        $char->user_id = Auth::user()->id;
        $char->x = 0;
        $char->y = 0;
        $char->save();
        $nodeService->generateSingleNode(0,0,4,$char->id);
        NodeStats::create([
           'char_id' => $char->id
        ]);
        return $char;
    }
}

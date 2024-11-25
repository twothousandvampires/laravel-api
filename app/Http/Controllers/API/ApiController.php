<?php

namespace App\Http\Controllers\API;

use App\Models\enemy;

class ApiController extends BaseController
{
    public function enemyList(): \Illuminate\Http\Response
    {
        $enemy_list = enemy::all()->pluck('name');
        return $this->sendResponse($enemy_list);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Models\enemy;

class ApiController extends BaseController
{
    public function enemy_list(){
        $enemy_list = enemy::all()->pluck('name');
        return $this->sendResponse($enemy_list);
    }
}

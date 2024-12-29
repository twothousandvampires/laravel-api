<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Fabrics\ActionsFabric;

class ApiController
{
    public function __invoke($action = false, Request $request): \Illuminate\Http\JsonResponse
    {
        if(!$action){
            return response()->json('no action', 200);
        }

        $action = ActionsFabric::createAction($action);

        if(!$action){
            return response()->json('wrong action', 200);
        }

        $check = $action->check($request);

        if($check['success']){
            $res = $action->do($request);
            return response()->json($res, 200);
        }
        else{
            return response()->json($check['message'], 400);
        }
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{

    public function sendResponse($result, $msg = ''): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'msg' => $msg
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = []): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, 200);
    }
}

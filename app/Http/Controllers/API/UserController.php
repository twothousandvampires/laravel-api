<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public function getUser(): \Illuminate\Http\JsonResponse
    {

        $user = User::find(Auth::user()->id);
        if (!$user) {
            return $this->sendError('User not found.');
        }
        $user->characters = $user->character()->get();

        return $this->sendResponse($user, 'user');

    }
}

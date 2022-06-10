<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public function getUser()
    {

        $user = User::find(Auth::user()->id);
        $user->characters = $user->character()->get();

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse($user,'user');

    }
}

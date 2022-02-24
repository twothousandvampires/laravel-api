<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
class UserController extends BaseController
{
    public function show($id)
    {

        $user = User::find($id);

        $user->characters = $user->character()->get();

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse($user,'user');
    }
}

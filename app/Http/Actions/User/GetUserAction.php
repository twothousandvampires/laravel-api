<?php 
namespace App\Http\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Actions\Action;

class GetUserAction extends Action
{
    public $character = false;

    public function do($request){

        $user = User::find(auth('api')->user()->id);

        if (!$user) {
            $this->setUnsuccess('User not found.');
        }
        else{
            $user->characters = $user->character()->get();
            $this->addData(['user' => $user]);
        }
        
        return $this->answer;
    }
}
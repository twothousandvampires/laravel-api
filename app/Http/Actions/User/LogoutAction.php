<?php 
namespace App\Http\Actions\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Actions\Action;

class LogoutAction extends Action
{
    public $auth = false;
    public $character = false;
 
    public function do($request){

        if(auth('api')->user()){
            $accessToken = auth('api')->user()->token();
            $token = auth('api')->user()->tokens->find($accessToken);
            $token->revoke();
        }
     
        return $this->answer;
    }
}
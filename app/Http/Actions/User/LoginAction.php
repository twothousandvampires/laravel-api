<?php 
namespace App\Http\Actions\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Actions\Action;

class LoginAction extends Action
{
    public $auth = false;
    public $character = false;
 
    public function do($request){

        if(Auth::attempt(['name' => $request->name, 'password' => $request->password])){
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;
            $this->addData(['token' => $token]);
        }
        else{
            $this->setUnsuccess('wrong password or email');
        }

        return $this->answer;
    }
}
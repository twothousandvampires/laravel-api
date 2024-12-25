<?php 
namespace App\Http\Actions;

use Illuminate\Support\Facades\Auth;

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
            $this->setUnsucces('wrong password or email');
        }

        return $this->answer;
    }
}
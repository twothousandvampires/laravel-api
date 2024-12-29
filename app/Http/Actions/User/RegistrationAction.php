<?php 
namespace App\Http\Actions\User;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use App\Http\Actions\Action;

class RegistrationAction extends Action
{
    public $auth = false;
    public $character = false;
 
    public function do($request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            $this->setUnsuccess('Validation Error.');
            return $this->answer;
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        User::create($input);

        return $this->answer;
    }
}
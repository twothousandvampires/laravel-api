<?php
namespace App\Http\Actions;

use Illuminate\Support\Facades\Auth;
use App\Models\Character;

abstract class Action
{
    public $auth = true;
    public $character = true;
    public $answer = [
        'message' => 'server message',
        'success' => true,
        'data' => []
    ];

    public function setUnsuccess($msg = null){
        $this->answer['success'] = false;
        if($msg){
            $this->answer['message'] = $msg;
        }
    }

    public function setSuccess($msg = null){
        $this->answer['success'] = true;
        if($msg){
            $this->answer['message'] = $msg;
        }
    }

    public function addData($data){
        $this->answer['data'] = $data;
    }

    public function check($request){

        $answer = ['success' => true, 'message' => ''];

        if(isset($this->requared_params)){
            foreach($this->requared_params as $param){
                if(!$request->has($param)){
                    $answer['message'] = 'missed requared param';
                    $answer['success'] = false;
                    return $answer;
                }
            }
        }

        if($this->auth){
            if(!auth('api')->check()){
                $answer['message'] = 'not auth';
                $answer['success'] = false;
                return $answer;
            }
        }

        if($this->character){
            if(is_null($request->char_id)){
                $answer['message'] = 'missed character id';
                $answer['success'] = false;
                return $answer;
            }
            else{
                $character =  Character::find($request->char_id);
                $user_id = Character::find($request->char_id)->user_id;

                if($user_id !== auth('api')->user()->id){
                    $answer['message'] = 'not accaunt character';
                    $answer['success'] = false;
                    return $answer;
                }
            }          
        }
        
        return $answer;
    }
}
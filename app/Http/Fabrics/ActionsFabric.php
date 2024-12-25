<?php
namespace App\Http\Fabrics;

use App\Http\Actions\TorchAction;
use App\Http\Actions\RestAction;
use App\Http\Actions\CreateItemAction;
use App\Http\Actions\LoginAction;
use App\Http\Actions\GetUserAction;
use App\Http\Actions\LogoutAction;
use App\Http\Actions\RegistrationAction;


class ActionsFabric
{
    static private $list_map = [
        'torch' => 'App\Http\Actions\TorchAction',
        'rest' => 'App\Http\Actions\RestAction',
        'create_item' => 'App\Http\Actions\CreateItemAction',   
        'login' => 'App\Http\Actions\LoginAction', 
        'user' => 'App\Http\Actions\GetUserAction',
        'logout' => 'App\Http\Actions\LogoutAction',
        'registration' => 'App\Http\Actions\RegistrationAction'
    ];

    static public function createAction($action_name)
    {
        return new ActionsFabric::$list_map[$action_name]();
    }
}

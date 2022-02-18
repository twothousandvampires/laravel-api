<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Character;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CharacterController extends BaseController
{
    public function create($id, Request $request){

        try{
            $char = new Character();
            $char->name = $request->name;
            $char->class = $request->class_name;
            $char->user_id = $id;
            $char->save();

            Schema::connection('mysql')->create('world' .$char->id, function($table)
            {
                $table->increments('id');
            });
        }
        catch(\Exception $e){

        }

        return $this->sendResponse($char, 'Product retrieved successfully.');
    }
}

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CharacterController;
use App\Http\Controllers\API\ItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);


Route::middleware('auth:api')->group( function () {
    Route::post('logout', [RegisterController::class, 'logout']);
    Route::post('character/create/', [CharacterController::class, 'create']);
    Route::post('user',[UserController::class, 'getUser'] )->middleware('cors');
    Route::post('character/{char_id}/world',[CharacterController::class, 'world']);
    Route::post('character/win/',[CharacterController::class, 'win']);
    Route::post('character/{char_id}/move/',[CharacterController::class, 'move']);
    Route::post('character/delete/',[CharacterController::class, 'delete']);
    Route::post('item/change/',[ItemController::class, 'change']);
    Route::post('item/create/',[ItemController::class, 'create']);
    Route::post('item/delete/',[ItemController::class, 'delete']);
    Route::post('item/use/{id}',[ItemController::class, 'use']);
});



//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

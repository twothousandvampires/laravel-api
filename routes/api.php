<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CharacterController;

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
    Route::post('character/create/{user_id}', [CharacterController::class, 'create']);
    Route::resource('user' ,UserController::class )->middleware('cors');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

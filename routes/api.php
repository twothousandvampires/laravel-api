<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CharacterController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\SkillController;
use App\Http\Controllers\API\ApiController;

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
Route::post('login', [RegisterController::class, 'login'])->name('login');
Route::post('logout', [RegisterController::class, 'logout']);
Route::get('enemy_list', [ApiController::class, 'enemy_list']);

Route::middleware('auth:api')->group( function () {

    Route::prefix('character')->group(function () {
        Route::post('/create/', [CharacterController::class, 'create']);

        Route::middleware('check')->group( function () {
            Route::get('/get/{char_id}', [CharacterController::class, 'get']);
            Route::get('/torch/{char_id}', [CharacterController::class, 'useTorch']);
            Route::post('/{char_id}/world',[CharacterController::class, 'world']);
            Route::post('/{char_id}/win/',[CharacterController::class, 'win']);
            Route::post('/{char_id}/move/',[CharacterController::class, 'move']);
            Route::post('/{char_id}/delete/',[CharacterController::class, 'delete']);
            Route::post('/set/{char_id}',[CharacterController::class, 'set']);
        });
    });

    Route::prefix('item')->group(function (){
        Route::get('/', [ItemController::class, 'getList']);
        Route::post('/change/',[ItemController::class, 'change']);
        Route::post('/create/',[ItemController::class, 'create']);
        Route::post('/delete/',[ItemController::class, 'delete']);
        Route::delete('/delete_all/',[ItemController::class, 'deleteAll']);
        Route::post('/use/{id}',[ItemController::class, 'use']);
        Route::post('/amplifications/',[ItemController::class, 'amplifications']);
    });

    Route::prefix('amplification')->group(function (){
        Route::post('/{id}/up', [SkillController::class, 'upAmplification']);
        Route::post('/{id}/upgrade', [SkillController::class, 'upgradeAmplification']);
    });

    Route::prefix('skill')->group(function (){
        Route::post('/{id}/up', [SkillController::class, 'upSkill']);
    });


    Route::post('user',[UserController::class, 'getUser'] )->middleware('cors');
});


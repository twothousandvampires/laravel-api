<?php

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

Route::post('/{action}', ApiController::class);

// Route::post('register', [RegisterController::class, 'register']);
// Route::post('login', [RegisterController::class, 'login'])->name('login');
// Route::post('logout', [RegisterController::class, 'logout']);
Route::get('enemy_list', [ApiController::class, 'enemyList']);

Route::middleware('auth:api')->group( function () {

    Route::prefix('character')->group(function () {
        // Route::post('/create/', [CharacterController::class, 'create']);

        Route::middleware('check')->group( function () {
            // Route::get('/get/{char_id}', [CharacterController::class, 'get']);
            // Route::post('/torch/{char_id}', [CharacterController::class, 'useTorch']);
            // Route::post('/{char_id}/world',[CharacterController::class, 'world']);
            // Route::post('/{char_id}/win/',[CharacterController::class, 'win']);
            // Route::post('/{char_id}/retreat/',[CharacterController::class, 'retreat']);
            // Route::post('/{char_id}/rest/{amount}',[CharacterController::class, 'rest']);
            // Route::post('/{char_id}/move/',[CharacterController::class, 'move']);
            // Route::post('/{char_id}/delete/',[CharacterController::class, 'delete']);
            // Route::post('/set/{char_id}',[CharacterController::class, 'set']);
            // Route::post('/get_passives/{char_id}',[CharacterController::class, 'getPassives']);
            // Route::post('/learn_passive/{char_id}/{passive_id}',[CharacterController::class, 'learnPassive']);
            // Route::post('/upgrade_passive/{char_id}/{passive_id}',[CharacterController::class, 'upgradePassive']);
            // Route::post('/set_started/{char_id}',[CharacterController::class, 'setStarted']);
        });
    });

    Route::prefix('item')->group(function (){
        // Route::get('/', [ItemController::class, 'getList']);
        // Route::post('/change/{char_id}',[ItemController::class, 'change']);
        // Route::post('/create/',[ItemController::class, 'create']);
        // Route::post('/delete/',[ItemController::class, 'delete']);
        // Route::delete('/delete_all/',[ItemController::class, 'deleteAll']);
        // Route::post('/use/{id}',[ItemController::class, 'use']);
        // Route::post('/amplifications/',[ItemController::class, 'amplifications']);
        // Route::post('/upgrade_quality/{char_id}/{item_id}/{used_id}',[ItemController::class, 'upgradeQuality']);
        // Route::post('/upgrade_effect/{char_id}/{item_id}/{used_id}',[ItemController::class, 'upgradeEffect']);
        // Route::post('/add_property/{char_id}/{item_id}/{used_id}',[ItemController::class, 'addProperty']);
        // Route::post('/use_items/{char_id}',[ItemController::class, 'useItems']);
        // Route::post('/synthesis/',[ItemController::class, 'createFromInv']);
        // Route::post('/create_shard/',[ItemController::class, 'createShard']);
    });

    Route::prefix('skill')->group(function (){
        // Route::post('/get_skills/{char_id}/{item_id}', [SkillController::class, 'getSkills']);
        // Route::post('/learn_skill/{char_id}/{used_id}', [SkillController::class, 'learnSkill']);
        // Route::post('/upgrade_skill/{char_id}/{used_id}', [SkillController::class, 'upgradeSkill']);
    });

    // Route::post('user', [UserController::class, 'getUser'] );
});


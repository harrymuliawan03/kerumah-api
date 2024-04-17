<?php

use App\Http\Controllers\PerumahanController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware(ApiAuthMiddleware::class)->group( function(){
    Route::get('/user/current', [UserController::class, 'getUser']);
    Route::patch('/user/current', [UserController::class, 'updateUser']);

    //perumahan 
    Route::get('/perumahan', [PerumahanController::class, 'getPerumahan']);
    Route::get('/perumahan/{id}', [PerumahanController::class, 'getPerumahanById']);
    Route::post('/perumahan', [PerumahanController::class, 'createPerumahan']);
    Route::patch('/perumahan', [PerumahanController::class, 'updatePerumahan']);
    Route::delete('/perumahan', [PerumahanController::class, 'deletePerumahan']);

    // units
    Route::get('/perumahan-units', [UnitController::class, 'getUnitsPerumahan']);
    Route::get('/perumahan-units/{id}', [UnitController::class, 'getUnitPerumahanById']);
    Route::post('/unit', [UnitController::class, 'createUnit']);

});

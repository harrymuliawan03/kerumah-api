<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KontrakanController;
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
Route::get('/user/current-byToken', [UserController::class, 'getUserByToken']);

Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::get('/user/current', [UserController::class, 'getUser']);
    Route::patch('/user/current', [UserController::class, 'updateUser']);

    //perumahan 
    Route::get('/perumahan', [PerumahanController::class, 'getPerumahan']);
    Route::get('/perumahan/{id}', [PerumahanController::class, 'getPerumahanById']);
    Route::post('/perumahan', [PerumahanController::class, 'createPerumahan']);
    Route::patch('/perumahan/{id}', [PerumahanController::class, 'updatePerumahan']);
    Route::delete('/perumahan', [PerumahanController::class, 'deletePerumahan']);

    //perumahan 
    Route::get('/kontrakan', [KontrakanController::class, 'getkontrakan']);
    Route::get('/kontrakan/{id}', [kontrakanController::class, 'getkontrakanById']);
    Route::post('/kontrakan', [kontrakanController::class, 'createkontrakan']);
    Route::patch('/kontrakan/{id}', [kontrakanController::class, 'updatekontrakan']);
    Route::delete('/kontrakan', [kontrakanController::class, 'deletekontrakan']);

    // units
    Route::post('/unit', [UnitController::class, 'createUnit']);
    Route::get('/units', [UnitController::class, 'getUnits']);
    Route::get('/unit/{id}', [UnitController::class, 'getUnitById']);
    Route::patch('/unit/{id}', [UnitController::class, 'updateUnit']);
    Route::delete('/unit/{id}', [UnitController::class, 'deleteUnit']);
    Route::post('/unit-payment/{id}', [UnitController::class, 'bayarUnit']);

    // dashboard
    Route::get('/data-calculation', [DashboardController::class, 'getCalculation']);
});

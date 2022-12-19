<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BoilerController;
use App\Http\Controllers\Api\DoorController;
use App\Http\Controllers\Api\LightController;
use App\Http\Controllers\Api\MatController;
use App\Http\Controllers\Api\LightLogController;
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

//Route::middleware('auth:sanctum')->get("/lightLog", [LightLogController::class, 'show']);


Route::prefix("/auth")->group(function(){
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/checkToken', [AuthController::class, 'checkToken']);
});


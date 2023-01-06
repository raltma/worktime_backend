<?php

use App\Http\Controllers\AbsentReportController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HourReportController;
use App\Http\Controllers\PieceReportController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/users',[UserController::class, 'index']);
Route::middleware('auth:sanctum')->get('/departments',[DepartmentController::class, 'index']);

Route::middleware('auth:sanctum')->apiResource('classification', ClassificationController::class);

Route::middleware('auth:sanctum')->apiResource('hourReport', HourReportController::class);
Route::middleware('auth:sanctum')->apiResource('pieceReport', PieceReportController::class);
Route::middleware('auth:sanctum')->apiResource('absentReport', AbsentReportController::class);

Route::get('/hourReportsByDate', [HourReportController::class, 'getByDate']);
Route::get('/absentReportsByDate', [AbsentReportController::class, 'getByDate']);


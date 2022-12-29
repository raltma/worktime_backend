<?php

use App\Http\Controllers\admin\AdminAuthController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('test');
});
Route::get('/login', function(){
    return view('login',['title'=>'Sisse logimine']);
})->name('login');
Route::post('/login', [AdminAuthController::class,'authenticate']);
Route::post('/update_user', [UserController::class,'updateUser']);
Route::get('/logout', [AdminAuthController::class,'logout']);
Route::middleware(['auth','auth.admin'])->get('/users', function(){
    $users = User::all();
    $departments = Department::all();
    return view('users',['title'=>'Kasutajad', 'users'=>$users, 'departments'=>$departments]);
});
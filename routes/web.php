<?php

use App\Http\Controllers\admin\AdminAuthController;
use App\Http\Controllers\AbsentReportController;
use App\Http\Controllers\HourReportController;
use App\Http\Controllers\PieceReportController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\Department;
use App\Models\HourReport;
use App\Models\PieceReport;
use App\Models\AbsentReport;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;

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
    return redirect('/user');
});
Route::get('/login', function(){
    return view('login',['title'=>'Sisse logimine']);
})->name('login');
Route::post('/login', [AdminAuthController::class,'authenticate']);
Route::get('/logout', [AdminAuthController::class,'logout']);

Route::middleware(['auth', 'auth.admin'])->prefix("/user")->group(function(){
    Route::get('/', function(){
        $users = User::where('bs_department_id', '!=','2')->get();
        $departments = Department::all();
        return view('users',['title'=>'Kasutajad', 'users'=>$users, 'departments'=>$departments]);
    });
    Route::post('/update', [UserController::class,'updateUser']);
});

Route::middleware(['auth', 'auth.admin'])->prefix("/hourReport")->group(function(){
    Route::get('/', function(){
        $adminDepartments = array_map(function($d){return $d['bs_id'];},auth()->user()->adminDepartments->toArray());
        if(in_array("0",$adminDepartments)){
            $reports = HourReport::all();
        }else{
            $reports = HourReport::whereHas('user', function(Builder $query){
                $adminDepartments = array_map(function($d){return $d['bs_id'];},auth()->user()->adminDepartments->toArray());
                $query->whereIn('bs_id', $adminDepartments);
            })
            ->orderByDesc('confirmed')
            ->orderByDesc('date_selected')
            ->get();
        }
        return view('hourReport',['title'=>'Tunni aruanded', 'reports'=>$reports]);
    });
    Route::post('/confirm', [HourReportController::class,'confirm']);
});

Route::middleware(['auth', 'auth.admin'])->prefix("/absentReport")->group(function(){
    Route::get('/', function(){
        $adminDepartments = array_map(function($d){return $d['bs_id'];},auth()->user()->adminDepartments->toArray());
        if(in_array("0",$adminDepartments)){
            $reports = AbsentReport::all();
        }else{
            $reports = AbsentReport::whereHas('user', function(Builder $query){
                $adminDepartments = array_map(function($d){return $d['bs_id'];},auth()->user()->adminDepartments->toArray());
                $query->whereIn('bs_id', $adminDepartments);
            })
            ->orderByDesc('confirmed')
            ->orderByDesc('date_selected')
            ->get();
        }
        return view('absentReport',['title'=>'Puudumiste aruanded', 'reports'=>$reports]);
    });
    Route::post('/confirm', [AbsentReportController::class,'confirm']);
});

Route::middleware(['auth', 'auth.admin'])->prefix("/piecesReport")->group(function(){
    Route::get('/', function(){
        $adminDepartments = array_map(function($d){return $d['bs_id'];},auth()->user()->adminDepartments->toArray());
        if(in_array("0",$adminDepartments)){
            $reports = PieceReport::all();
        }else{
            $reports = PieceReport::whereHas('user', function(Builder $query){
                $adminDepartments = array_map(function($d){return $d['bs_id'];},auth()->user()->adminDepartments->toArray());
                $query->whereIn('bs_id', $adminDepartments);
            })
            ->orderByDesc('confirmed')
            ->orderByDesc('date_selected')
            ->get();
        }
        return view('piecesReport',['title'=>'Tükkide aruanded', 'reports'=>$reports]);
    });
    Route::post('/confirm', [PieceReportController::class,'confirm']);
});

Route::get("/api/absentReasons", function(){
    return response()->file(resource_path('json/absentReasons.json'));
});
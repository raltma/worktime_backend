<?php

use App\Http\Controllers\admin\AdminAuthController;
use App\Http\Controllers\AbsentReportController;
use App\Http\Controllers\HourReportController;
use App\Http\Controllers\PieceReportController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\Department;
use App\Models\Classification;
use App\Models\HourReport;
use App\Models\PieceReport;
use App\Models\AbsentReport;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\File;

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
Route::get('/home', function(){
    return redirect(auth()->user()->default_tab);
});
Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login', function(){
    if(!Auth::check()){
        return view('login',['title'=>'Sisse logimine']);
    }
    return redirect('/home');
})->name('login');
Route::post('/login', [AdminAuthController::class,'authenticate']);
Route::get('/logout', [AdminAuthController::class,'logout']);

Route::middleware(['auth', 'auth.admin'])->prefix("/user")->group(function(){
    Route::get('/', function(){
        $adminDepartments = array_map(function($d){return $d['bs_id'];},auth()->user()->adminDepartments->toArray());
        if(in_array("0",$adminDepartments)){
            $users = User::where('bs_department_id', '!=','2')->get();
            $departments = Department::all();
            return view('users',['title'=>'Kasutajad', 'users'=>$users, 'departments'=>$departments]);
        }else{
            return redirect('/hourReport');
        }
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
    Route::get('/update/{id}', function($id){
        $report = HourReport::find($id);
        $users = User::all()->sortBy("name");
        return view('hourReportUpdate', ['users'=>$users, 'report'=>$report, 'title'=>'Tundide aruande muutmine']);
    });
    Route::post('/update',[HourReportController::class, 'update']);
    Route::post('/delete/{id}', [HourReportController::class,'delete']);
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
            ->orderByDesc('date_start')
            ->get();
        }
        return view('absentReport',['title'=>'Puudumiste aruanded', 'reports'=>$reports]);
    });
    Route::post('/confirm', [AbsentReportController::class,'confirm']);
    Route::get('/update/{id}', function($id){
        $report = AbsentReport::find($id);
        $reasons = json_decode(File::get(resource_path("json/absentReasons.json")));
        $users = User::all()->sortBy("name");;
        return view('absentReportUpdate', [
            'users'=>$users,
            'report'=>$report, 
            'reasons'=>$reasons, 
            'title'=>'Puudumiste aruande muutmine']);
    });
    Route::post('/update',[AbsentReportController::class, 'update']);
    Route::post('/delete/{id}', [AbsentReportController::class,'delete']);
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
    Route::get('/update/{id}', function($id){
        $report = PieceReport::find($id);
        $classifications = Classification::all();
        $workplaces = json_decode(File::get(resource_path("json/workplaces.json")));
        $users = User::all()->sortBy("name");;
        return view('piecesReportUpdate', [
            'users'=>$users,
            'report'=>$report, 
            'classifications'=>$classifications, 
            'workplaces'=>$workplaces,
            'title'=>'Tükkide aruande muutmine']);
    });
    Route::post('/update',[PieceReportController::class, 'update']);
    Route::post('/delete/{id}', [PieceReportController::class,'delete']);
});

Route::get("/api/absentReasons", function(){
    return response()->file(resource_path('json/absentReasons.json'));
});
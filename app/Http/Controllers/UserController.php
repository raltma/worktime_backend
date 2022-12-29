<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AdminDepartment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function index(){
        $user = User::all();
        return response()->json($user);
    }

    function updateUser(Request $request){
        $validate = Validator::make($request->all(),['id'=> 'required']);
        if($validate->fails()) return back()->withErrors([
                'error' => 'Kasutaja polnud valitud!']);
        $input = $request->all();     
        if(auth()->user()->admin === 1){
            $user = User::find($input['id']);
            
            if(isset($input['username'])) $user->username = $input['username'];
            if(isset($input['password']))$user->password = Hash::make($input['password']);
            $user->adminDepartments()->detach();
            if(isset($input['admin'])){
                if(isset($input['adminDepartments'])){
                    foreach($input['adminDepartments'] as $department){
                        $user->adminDepartments()->attach($department);
                    }
                }
            }
            
            $user->admin = isset($input['admin'])?1:0;
            $user->save();
            return back()->withErrors(['message'=>"Kasutaja \"{$user['username']}\" muudetud"]);
        }
        return back()->withErrors([
            'error' => 'Kasutajal pole admini õigust!',
        ])->onlyInput('message');
    }
}  

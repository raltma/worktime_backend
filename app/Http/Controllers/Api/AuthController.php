<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function create(Request $request){
        try{
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'username' => 'required|unique:users,username',
                'password' => 'required'
            ]);

            if($validateUser->fails()) return response()->json([
                'status' => false,
                'message' => 'validation error',
                'error' => $validateUser->errors()
            ], 401);

            $user = User::create([
                'username'=> $request->username,
                'password'=> Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User created successfully'
            ], 200);
            
        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function login(Request $request){
        try {
            $validateUser = Validator::make($request->all(),
            [
                'username'=> 'required',
                'password' => 'required'
            ]);

            if($validateUser->fails()) return response()->json([
                'status' => false,
                'message' => 'validation error',
                'error' => $validateUser->errors()
            ], 401);
            
            if(!Auth::attempt($request->only(['username','password'])))return response()->json([
                'status' => false,
                'message' => 'Username and password do not match',
                'error' => $validateUser->errors()
            ], 401);

            $user = User::where('username', $request->username)->first();

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
            ], 200);
        } catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

     public function checkToken(Request $request){
        try {
            $user =  $request->user(); 
            return response()->json([
                'user' => $user
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    } 
}
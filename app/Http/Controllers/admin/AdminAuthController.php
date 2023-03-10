<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            if(auth()->user()->admin === 1 && auth()->user()->bs_department_id !== "2"){
                $request->session()->regenerate();
                return redirect('/home');
            }
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors([
                'message' => 'Kasutajal pole admini õigust',
            ])->onlyInput('message');
        }
        return back()->withErrors([
            'message' => 'Kasutajanimi või parool ei sobinud',
        ])->onlyInput('message');
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/login');
    }
}

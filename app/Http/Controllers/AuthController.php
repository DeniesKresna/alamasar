<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

	public function index(){
        if(Auth::check()){
            return redirect()->route('home');
        }
        else{
		  return view('login');
        }
	}

    public function attempt(Request $request){
        $attempts = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        //if (Auth::attempt($attempts, (bool) $request->remember)) {
       	if (Auth::attempt($attempts)) {
            return redirect()->route('home');
        }
        return view('login',['message'=> 'Email dan Password anda salah']);
    }

    public function logout(){
    	Auth::logout();
    	return redirect()->route('auth.page');
    }
}

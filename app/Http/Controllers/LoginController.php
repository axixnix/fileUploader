<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;


class LoginController extends Controller
{
    //
    public function login(Request $request){
        //$user = User::Where('email',$request->input('identifier'))->orWhere('name',$request->input('identifier'))->first();
        $user = User::where('email', $request->input('identifier'))
            ->orWhere('name', $request->input('identifier'))
            ->first();
        if(is_null($user)) return response(['message'=>'user credentials do not kkkkk match our records'],401);

        if(Hash::check($request->input('password'), $user->password)){
            $token = Crypt::encryptString($user->id);
            return response(['message'=>'logged in successfully','token'=>$token]);
        }else{
            return response(['message'=>'user credentials do not match our records'],401);
        }
    }

    public function logout(){
        Auth::logout();
        return response(['message'=>'logout successful'],200);
    }
}

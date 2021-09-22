<?php

namespace App\Http\Controllers;

use App\Mail\VerifyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Verification;
use Illuminate\Support\Facades\Hash;
use App\Utils\RandomFunctions;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    //
    public function registerUser(Request $request){
        $validator= Validator::make($request->all(),[
            'email'=>'required|string|email|unique:users',
            'password'=>'required|string'

        ]);

        if($validator->fails()){
            return response(['message'=>$validator->errors()->first()],422);
        }

        $user = User::create([])

    }
}

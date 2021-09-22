<?php

namespace App\Http\Controllers;

use App\Mail\VerifyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Support\Facades\Hash;
use App\Utils\RandomFunctions;
use Illuminate\Auth\Notifications\VerifyEmail;
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

        $user = User::create([
            'name'=>'',
            'email'=>$request->input('email'),
            'password'=>Hash::make($request->input('password'))
        ]);

        $verification = new Verification;
        $verification->payload = RandomFunctions::generateRandomString(5);
        $check = true;
        do{
            $check = !is_null(Verification::where('payload',$verification->payload)->first());
        }while($check);

        $user->verifications()->save($verification);

        Mail::to($user->email)->send(new VerifyMail($user,$verification));

        return response(['message'=>'user created successfully'],200);

    }
}

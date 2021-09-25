<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verification;
use App\Models\User;
use App\Utils\RandomFunctions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    //
    private function verifyVerification(Verification $verification): bool
    {
        if($verification->has_been_used){
            throw new \Exception("code has already been used");
        }else{
            $now = Carbon::now();
            $created = Carbon::parse($verification->created_at);
            if($created->diffInMinutes($now) >= 30){
                throw new \Exception("code is expired");
            }else{
                $verification->has_been_used = true;
                $verification->time_used = Carbon::now();
                $verification->save();
                return true;
            }
        }
    }

    public function verifyEmail(Request $request){
        try{
            $verification = Verification::Where('payload',$request->input('payload'))->firstOrFail();
            $this->verifyVerification($verification);
            $user=$verification->user;
            $user->email_verified_at = Carbon::now();
            $user->save();
            return response(['message'=>'email verification successful'],200);
        }catch(\Exception $exp){
            return response(['message'=>$exp->getMessage()],400);

        }

    }

    public function resetPassword(Request $request){
        $email= $request->input('email');
        $user = User::Where('email',$email)->first();
        if(!is_null($user)){
            $verification =new Verification;
            $verification->payload = RandomFunctions::generateRandomString(5);
            $check = true;
            do{
                $check = !is_null(Verification::where('payload',$verification->payload)->first());
            }while($check);
            $user->verifications()->save($verification);
            Mail::to($user->email)->send(new \App\Mail\ResetPassword($user,$verification));
        }

        return response(['message'=>'if you have an account we have sent a mail to reset your password'],200);
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'code'=>['string','required'],
            'password'=>['string','required'],
            'password_again'=>['string','required','same:password']
        ]);

        if($validator->fails()){
            return response(['message'=>$validator->errors()],422);
        }
        try{
            $verification = Verification::Where('payload',$request->input('code'))->firstOrFail();
            $this->verifyVerification($verification);
            $user = $verification->user;
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return response(['message' => "Password changed successfully"], 200);
        } catch (ModelNotFoundException $exp) {
            return response(['message' => "Invalid code entered"], 400);
        } catch (\Exception $exp) {
            return response(['message' => $exp->getMessage()], 400);
        }
    }
}

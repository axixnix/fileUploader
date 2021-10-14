<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;

class CustomAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->hasHeader('Authorization')){
            try{
                $header= $request->header('Authorization');
                $value = Crypt::decryptString($header);
                $user = User::find($value);
                //var_dump($user);

                if($value && $user){
                    $request->attributes->add(['user'=>$user]);
                    Auth::onceUsingId($value);
                    return $next($request);
                }else{
                    return response(['message'=>'unauthenticated'],401);
                }
            }catch(DecryptException $exp){
                echo $exp->getMessage();
                return response(['message'=>'unauthenticated'],401);
            }
        }
        return response(['message'=>'unauthenticated'],401);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use \Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class authJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        try {

            $user = JWTAuth::toUser($request->input('token'));

        } catch (Exception $e) {

            if ($e instanceof TokenInvalidException){

                return response()->json(['error'=>'Token is Invalid']);

            }else if ($e instanceof TokenExpiredException){

                return response()->json(['error'=>'Token is Expired']);

            }else{
                //dd($e);
                return response()->json(['error'=>$e->getMessage()]);

            }

        }


        return $next($request);
    }
}

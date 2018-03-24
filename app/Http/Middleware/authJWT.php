<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use JWTAuth;

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
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['error'=>'El Token is Invalido']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['error'=>'El Token ha Expiredo']);
            }else{
                return response()->json(['error'=>'Algo esta mal, reportar al programador']);
            }
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class adminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            if($user && $user->role == 'admin'){
                return $next($request);
            }
            return response()->json([
                'error' => 'Unauthorized: You do not have the required permissions.'
            ],403);
        }catch(Exception $e){
            abort(401 , 'Error failed get Token'. $e->getMessage());
        }
    }
}

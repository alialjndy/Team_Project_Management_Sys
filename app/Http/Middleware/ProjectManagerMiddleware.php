<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProjectManagerMiddleware
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
            $projectId = $request->route('project_id');
            if($user && $user->projects()->where('project_id', $projectId)->wherePivot('role', 'manager')->exists()){
                return $next($request);
            }else{
                abort(403, 'Unauthorized: You do not have the required permissions.');
            }
        }catch(Exception $e){
            throw new Exception('Error failed get Token'.$e->getMessage());
        }
    }
}

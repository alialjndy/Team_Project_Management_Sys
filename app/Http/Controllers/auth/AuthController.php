<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'error' => 'Invalid Credentials'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'token' => $token
        ], 200);
    }
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'status' => 'success',
                'message' => 'User logged out successfully'
            ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to logout, token invalid or missing'
            ], 401);
        }
    }
    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json([
                'status' => 'success',
                'name'=>$user->name,
                'email' => $user->email,
                'role'=>$user->role
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to authenticate'
            ], 401);
        }
    }
}

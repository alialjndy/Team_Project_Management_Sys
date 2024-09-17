<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\AuthRequest;
use App\Http\Requests\Auth\UpdateAuthRequest;
use App\Models\User;
use App\Service\CrudUserService;
use Exception;
use Illuminate\Http\Request;

class CrudUserController extends Controller
{
    protected $crudUserService;
    public function __construct(CrudUserService $crudUserService){
        $this->crudUserService = $crudUserService;
    }
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $filterUser = $this->crudUserService->filter($request);
        return response()->json([
            'status' => 'success',
            'data' => $filterUser
        ], 200);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuthRequest $request)
    {

        $user = $this->crudUserService->createUser($request->validated());
        return response()->json([
            'status'=>'success',
            'message'=>'user created successfully',
            'data'=>$user
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->crudUserService->showUser($id);
        if($user){
            return response()->json([
                'status'=>'success',
                'data'=>[
                    'name'=>$user->name ,
                    'email'=>$user->email,
                    'role'=>$user->role
                ]
            ]);
        }
            return response()->json([
                'status'=>'failed',
                'message'=>'There is an error in server'
            ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $updatedUser = $this->crudUserService->updateUser($validatedData,$id);
        $updatedUser = User::findOrFail($id);
        if($updatedUser){
            return response()->json([
                'status'=>'success',
                'message'=>'user updated successfully',
                'data'=>[
                    'name'=>$updatedUser->name,
                    'email'=>$updatedUser->email,
                    'role'=>$updatedUser->role
                ]
            ],200);
        }
            return response()->json([
                'status'=>'failed',
                'message'=>'There is an error in server'
            ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->crudUserService->softDelete($id);
        return response()->json([
            'status'=>'success',
            'message'=>'user soft deleted successfully',
        ],200);
    }
    public function restore($id){
        $restoreUser =  $this->crudUserService->restoreUser($id);
        return response()->json([
            'status'=>'success',
            'message'=>'user restored successfully',
        ],200);
    }
    public function forceDelete($id){
        $this->crudUserService->forceDelete($id);
        return response()->json([
            'status'=>'success',
            'message'=>'user force deleted successfully',
        ],200);
    }
}

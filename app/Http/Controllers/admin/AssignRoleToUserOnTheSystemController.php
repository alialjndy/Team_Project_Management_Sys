<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignRoleToUserOnTheSystemRequest;
use App\Service\CrudUserService;
use Illuminate\Http\Request;

class AssignRoleToUserOnTheSystemController extends Controller
{
    protected $crudUserService;
    public function __construct(CrudUserService $crudUserService){
        $this->crudUserService = $crudUserService;
    }
    /**
     * Summary of AssignRoleToUserOnTheSystem
     * @param \App\Http\Requests\Admin\AssignRoleToUserOnTheSystemRequest $request
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function AssignRoleToUserOnTheSystem(AssignRoleToUserOnTheSystemRequest $request){
        // Validate Incoming Data
        $validatedData = $request->validated();

        // Get user ID
        $user_id = $validatedData['user_id'];

        // Get Role form the incoming data
        $role = $validatedData['role'];

        // Updated (System Role) to user
        $user = $this->crudUserService->AssignRoleToUserOnTheSystem($role,$user_id);
        return response()->json([
            'status'=>'success',
            'message'=>'(System Role) Assigned Successfully',
            'new Role'=>$user->role
        ],200);
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignProjectToUserRequest;
use App\Models\Project;
use App\Models\Project_User;
use App\Service\AssignProjectToUserService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssignProjectToUserController extends Controller
{
    protected $assignProjectToUserService;
    /**
     * Summary of __construct
     * @param \App\Service\AssignProjectToUserService $assignProjectToUserService
     */
    public function __construct(AssignProjectToUserService $assignProjectToUserService){
        $this->assignProjectToUserService = $assignProjectToUserService;
    }
    /**
     * Summary of assign
     * @param \App\Http\Requests\Admin\AssignProjectToUserRequest $request
     * @param mixed $project_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assign(AssignProjectToUserRequest $request, $project_id){
        //get incoming data for AssingProjectToUserRequest
        $validatedData = $request->validated();

        // Assing project to user
        $project = $this->assignProjectToUserService->assignProjectToUser($validatedData , $project_id);


        return response()->json([
            'status'=>'success',
            'data'=>'project assigned successfully'
        ],200);
    }
    /**
     * Summary of getProjectUsers
     * @param mixed $projectId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getProjectUsers($projectId){
            $projects = $this->assignProjectToUserService->getProjectUsers($projectId);
            return response()->json([
                'status'=>'success',
                'data'=>$projects
            ],200);
    }
}

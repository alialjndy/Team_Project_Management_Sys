<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CrudProjectReqeust;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Http\Requests\Task\GetHighestPriorityRequest;
use App\Models\Project;
use App\Models\Project_User;
use App\Service\CrudProjectService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CrudProjectController extends Controller
{
    protected $crudProjectService;
    public function __construct(CrudProjectService $crudProjectService){
        $this->crudProjectService = $crudProjectService;
    }
    /**
     * Summary of index
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $projects = $this->crudProjectService->filter($request);
        return response()->json([
            'status'=>'success',
            'message'=>'all Projects',
            'data'=>$projects
        ],200);
    }

    /**
     * Summary of store
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\Admin\CrudProjectReqeust $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(CrudProjectReqeust $request)
    {
        $valdiatedData = $request->validated();
        $project = $this->crudProjectService->CreateProject($valdiatedData);
        return response()->json([
            'status'=>'success',
            'message'=>'Project Created Successfully',
            'data'=>[
                'name'=>$project->name,
                'description'=>$project->description,
                'start_date'=>$project->start_date,
                'end_date'=>$project->end_date,
                'status'=>$project->status,
                'project_manager_id'=>$project->project_manager_id
            ]
        ],201);
    }

    /**
     * Display the specified resource.
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $project = $this->crudProjectService->showProject($id);
        return response()->json([
            'status'=>'success',
            'data'=>[
                'name'=>$project->name,
                'description'=>$project->description,
                'start_date'=>$project->start_date,
                'end_date'=>$project->end_date,
                'status'=>$project->status,
                'project_manager_id'=>$project->project_manager_id
            ]
        ],200);
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\Admin\UpdateProjectRequest $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateProjectRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $udpateProject = $this->crudProjectService->updateProject($validatedData,$id);
        $udpateProject = Project::findOrFail($id);
        if($udpateProject){
            return response()->json([
            'status'=>'success',
            'data'=>[
                'name'=>$udpateProject->name,
                'description'=>$udpateProject->description,
                'start_date'=>$udpateProject->start_date,
                'end_date'=>$udpateProject->end_date,
                'status'=>$udpateProject->status,
                'project_manager_id'=>$udpateProject->project_manager_id
            ]
        ],200);
        }
        return response()->json([
            'status'=>'failed',
            'message'=>'An Error Occured'
        ],403);
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $this->crudProjectService->softDelete($id);
        return response()->json([
            'status'=>'success',
            'message'=>'project soft deleted successfully'
        ],status: 200);
    }
    /**
     * Summary of restore
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function restore($id){
        $projectRestored = $this->crudProjectService->restore($id);
        return response()->json([
            'status'=>'sucess',
            'message'=>'project restored successfully'
        ],200);
    }
    /**
     * Summary of forceDelete
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function forceDelete($id){
        $this->crudProjectService->forceDelete($id);
        return response()->json([
            'status'=>'sucess',
            'message'=>'project force Deleted successfully'
        ],200);
    }

    public function getLastTaskRelatedWithProject(string $project_id){
        $project = Project::findOrFail($project_id);
        if($project){
            return response()->json([
                'status'=>'success',
                'data'=>$project->lastTask
            ],200);
        }else{
            return response()->json([
                'status'=>'failed',
                'data'=>'An Error Occurred.'
            ],401);
        }
    }
    public function getOldestTaskRelatedWithProject(string $project_id){
        $project = Project::findOrFail($project_id);
        if($project){
            return response()->json([
                'status'=>'success',
                'data'=>$project->oldTask
            ],200);
        }else{
            return response()->json([
                'status'=>'failed',
                'data'=>'An Error Occurred.'
            ],401);
        }
    }
    public function getHighestPriority(GetHighestPriorityRequest $request){

        $validatedData = $request->validated();
        $task = $this->crudProjectService->getHighestPriority($validatedData);
        if($task){
        return response()->json([
                'status'=>'success',
                'data'=>$task
            ],200);
        }
    }
}

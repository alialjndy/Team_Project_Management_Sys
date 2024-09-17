<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\AddNoteToTaskRequest;
use App\Service\CrudTaskService;
use Illuminate\Http\Request;
use App\Http\Requests\Task\AssignTaskToDeveloperRequest;
use App\Http\Requests\Task\changeStatusRequest;
use App\Models\Task;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeveloperAndTesterController extends Controller
{
    protected $crudTaskService;
    public function __construct(CrudTaskService $crudTaskService){
        $this->crudTaskService = $crudTaskService;
    }
    /**
     * Summary of AssignTaskToDeveloper
     * @param \App\Http\Requests\Task\AssignTaskToDeveloperRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function AssignTaskToDeveloper(AssignTaskToDeveloperRequest $request){
        $validatedData = $request->validated();

        //Get info
        $user_id = $validatedData['user_id'];
        $project_id = $validatedData['project_id'];
        $task_id = $validatedData['task_id'];

        // Assgin Task to developer
        $this->crudTaskService->AssignTaskToDeveloper($project_id , $user_id , $task_id);
        return response()->json([
            'status'=>'success',
            'message'=>'Task Assigned Successfully',
        ],200);
    }
    /**
     * Summary of changeStatusByDeveloper
     * @param \App\Http\Requests\Task\changeStatusRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function changeStatusByDeveloper(changeStatusRequest $request){
        $validatedData = $request->validated();

        $task_id = $validatedData['task_id'];

        $this->crudTaskService->changeStatus($validatedData);

        $task = Task::findOrFail($task_id);
        return response()->json([
            'status'=>'success',
            'message'=>'status change Successfully',
            'data'=>$task
        ],200);
    }
    /**
     * Summary of AddNote
     * @param \App\Http\Requests\Task\AddNoteToTaskRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function addNote(AddNoteToTaskRequest $request)
    {
        $validatedData = $request->validated();

        $this->crudTaskService->addNoteToTask($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Note Added Successfully',
        ], 200);
    }
    /**
     * Summary of getLastTask
     * @return mixed
     */
    public function getLastTask(){
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id ;

        $user = User::findOrFail($user_id);
        return $user->lastTask;
    }
    /**
     * Summary of getoldTask
     * @return mixed
     */
    public function getoldTask(){
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id ;

        $user = User::findOrFail($user_id);
        return $user->oldTask;
    }
}

<?php

namespace App\Http\Controllers;


use App\Http\Requests\Task\CrudTaskRequest;
use App\Http\Requests\Task\updateTaskRequest;
use App\Models\Task;
use App\Service\CrudTaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $crudTaskService;
    public function __construct(CrudTaskService $crudTaskService){
        $this->crudTaskService = $crudTaskService ;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allTask = $this->crudTaskService->filter($request);
        return response()->json([
            'status'=>'success',
            'All Tasks'=>$allTask
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrudTaskRequest $request,string $project_id)
    {
        $validatedData = $request->validated();
        $task = $this->crudTaskService->createTask($validatedData, $project_id);
        return response()->json([
            'status'=>'success',
            'data'=>$task
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $project_id, string $task_id)
    {
        $task = Task::where('id', $task_id)
                    ->where('project_id', $project_id)
                    ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $task
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $project_id, string $task_id)
    {
        $validatedData = $request->validated();
        $updatedTask = $this->crudTaskService->updateTask($validatedData, $project_id, $task_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Task Updated Successfully',
            'data' => $updatedTask
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->crudTaskService->deleteTask($id);
        return response()->json([
            'status'=>'success',
            'message'=>'Task Deleted Successfully',
        ],200);
    }

}

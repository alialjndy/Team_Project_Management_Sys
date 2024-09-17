<?php
namespace App\Service;

use App\Http\Requests\Task\changeStatusRequest;
use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class CrudTaskService{
    /**
     * Summary of filter
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filter(Request $request){
        $query = Task::query();
        if($request->has('status')){
            $query->whereRelation('task','status','=',$request->status);
        }
        if($request->has('priority')){
            $query->whereRelation('tasks','priority','=',$request->priority);
        }
        return $query->get();
    }
    /**
     * Summary of getTaskById
     * @param mixed $id
     * @throws \Exception
     */
    public function getTaskById($id){
        try{
            $task = Task::findOrFail($id);
            return $task;
        }catch(Exception $e){
            Log::error('Error when get Task by ID'.$e->getMessage());
            throw new Exception('There is an error in server'.$e->getMessage());
        }
    }
    /**
     * Summary of createTask
     * @param array $data
     * @throws \Exception
     * @return
     */
    public function createTask(array $data, string $project_id)
    {
        try {
            $data['project_id'] = $project_id;
            return Task::create($data);
        } catch (Exception $e) {
            Log::error('Error when creating task: ' . $e->getMessage());
            throw new Exception('There was an error creating the task.');
        }
    }
    /**
     * Summary of updateTask
     * @param array $data
     * @param mixed $id
     * @throws \Exception
     * @return mixed
     */
    public function updateTask(array $data, string $project_id, string $task_id)
    {
        try {
            $task = Task::where('id', $task_id)
                        ->where('project_id', $project_id)
                        ->firstOrFail();



            $task->update($data);

            return $task;

        } catch (Exception $e) {
            Log::error('Error when updating task: ' . $e->getMessage());
            throw new Exception('There was an error updating the task.');
        }
    }
    /**
     * Summary of deleteTask
     * @param mixed $id
     * @throws \Exception
     * @return void
     */
    public function deleteTask($id){
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->id ;

            $task = Task::findOrFail($id);
            if($user_id !== $task->manager_id){
                throw new Exception('User ID does not match the authenticated user.');
            }

            $task->delete();
        }catch(Exception $e){
            Log::error('Error when delete Task'.$e->getMessage());
            throw new Exception('There is an error in server'.$e->getMessage());
        }
    }
    /**
     * Summary of AssignTaskToDeveloper
     * @param mixed $project_id
     * @param mixed $user_id
     * @param mixed $task_id
     * @throws \Exception
     * @return void
     */
    public function assignTaskToDeveloper($project_id, $user_id, $task_id)
    {
        $userIsDeveloper = User::whereHas('projects', function ($query) use ($project_id) {
            $query->where('project_id', $project_id)
                ->where('role', 'developer');
        })->where('id', $user_id)->exists();

        $taskIsRelated = Task::where('id', $task_id)
                            ->whereHas('project', function ($query) use ($project_id) {
                                $query->where('project_id', $project_id);
                            })->exists();

        if ($userIsDeveloper && $taskIsRelated) {
            $task = Task::findOrFail($task_id);

            $task->to_assigned = $user_id;

            $task->status = 'in_progress';
            $task->save();
        } else {
            throw new Exception('The user is not a developer in this project or the task is not related to this project.');
        }
    }
    /**
     * Summary of changeStatus
     * @param array $data
     * @throws \Exception
     * @return void
     */
    public function changeStatus(array $data){
        $user = JWTAuth::parseToken()->authenticate();
        $user_id = $user->id;

        $task_id = $data['task_id'];
        $newStatus = $data['status'];

        $task = Task::findOrFail($task_id);
        if($user_id !== $task->to_assigned){
            throw new Exception('User ID does not match the authenticated user.');
        }
        $task->status = $newStatus ;
        $task->save();
    }
    public function addNoteToTask(array $data)
    {
        $project_id = $data['project_id'];
        $task_id = $data['task_id'];
        $observation = $data['observation'];

        $task = Task::where('id', $task_id)
                    ->where('project_id', $project_id)
                    ->firstOrFail();

        $task->observation = $observation;
        $task->save();
    }
}
?>

<?php
namespace App\Service;

use App\Models\Project;
use App\Models\Project_User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class CrudProjectService{
    /**
     * Summary of filter
     * @param \Illuminate\Http\Request $request
     * @throws \Exception
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filter(Request $request){
        try{
            $query = Project::query();
            if($request->has('status')){
                $query->where('status',$request->get('status'));
            }
            return $query->get();
        }
        catch(Exception $e){
            Log::error('Error in Filtering user'.$e->getMessage());
            throw new Exception('Error occured in the server');
        }
    }
    /**
     * Summary of CreateProject
     * @param array $data
     * @return
     */
    public function CreateProject(array $data){
        $data['start_date'] = now();
        $project = Project::create($data);
        return $project;
    }
    //
    /**
     * Summary of updateProject
     * @param array $data
     * @param string $id
     * @throws \Exception
     * @return mixed
     */
    public function updateProject(array $data , string $id){
        try{
            $project = Project::findOrFail($id);
            return $project->update($data);
        }catch(Exception $e){
            Log::error('Error in update Project'. $e->getMessage());
            throw new Exception('There is an error in server');
        }
    }
    /**
     * Summary of showProject
     * @param mixed $id
     * @throws \Exception
     */
    public function showProject($id){
        try{
            $project = Project::find($id);
            return $project;
        }catch(Exception $e){
            Log::error('Error in show Project'.$e->getMessage());
            throw new Exception('There is an error in server');
        }

    }
    /**
     * Summary of softDelete
     * @param mixed $id
     * @throws \Exception
     * @return void
     */
    public function softDelete($id){
        try{
            $project = Project::find($id);
            if(!$project){
                throw new Exception('Project not found');
            }
            $project->delete();
        }catch(Exception $e){
            Log::error('Error in deleting project: ' . $e->getMessage());
            throw new Exception('There is an error in server');
        }
    }
    /**
     * Summary of restore
     * @param mixed $id
     * @throws \Exception
     * @return mixed
     */
    public function restore($id){
        try{
            $project = Project::withTrashed()->findOrFail($id);
            $restoreProject = $project->restore();
            return $restoreProject;
        }catch(Exception $e){
            Log::error('Error in restore project'.$e->getMessage());
            throw new Exception('There is an error in server');
        }
    }
    /**
     * Summary of forceDelete
     * @param mixed $id
     * @throws \Exception
     * @return void
     */
    public function forceDelete($id){
        try{
            $project = Project::withTrashed()->findOrFail($id);
            $project->forceDelete();
        }catch(Exception $e){
            Log::error('Error in forceDelete project'.$e->getMessage());
            throw new Exception('There is an error in server');
        }
    }
    public function getHighestPriority(array $data){
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->id ;

            $titleCondition = $data['titleCondition'];
            $project = Project::findOrFail($data['project_id']);

            $record = Project_User::where('project_id',$data['project_id'])
                    ->where('user_id', $user_id)
                    ->where('role','manager')->first();
            if($record){
                $task = $project->highestPriorityWithCondition($titleCondition);
                return $task ;
            }
        }catch(Exception $e){
            throw new Exception('There is an error in server'.$e->getMessage());
        }
    }


}
?>

<?php
namespace App\Service;

use App\Models\Project;
use App\Models\Project_User;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssignProjectToUserService{
    /**
     * Summary of assignProjectToUser
     * @param array $data
     * @param mixed $project_id
     * @throws \Exception
     * @return mixed
     */
    public function assignProjectToUser(array $data ,$project_id){
        try{
            $user_id = $data['user_id'];
            $role = $data['role'];
            $project = Project::findOrFail($project_id);
            if(!$project){
                throw new Exception('Project not found');
            }
            $user = User::findOrFail($user_id);
            if(!$user){
                throw new Exception('User not found');
            }
            $project->status = 'in_progress';
            $project->save();
            return $project->users()->attach($user_id , ['role' => $role]);
        }catch(Exception $e){
            Log::error('SomeThing Wrong when assign project');
            throw new Exception('There is an error in server');
        }

    }
    /**
     * Summary of getProjectUsers
     * @param mixed $project_id
     * @throws \Exception
     * @return Project|\Illuminate\Database\Eloquent\Collection
     */
    public function getProjectUsers($project_id){
        try{
            // Authenticate the user using JWT
            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->id ;

            // Find Record where Assign Authenticate Info
            $record = Project_User::where('project_id',$project_id)
                ->where('user_id',$user_id)
                ->where('role','manager')
                ->first();

            if(!$record){
                throw new Exception('some thing wrong');
            }

            //Get All Projects Related with team user
            $project = Project::with(['users'=>function ($query){
                $query->select('users.id', 'users.name', 'users.email', 'users.role');
            }])
            ->find($project_id);

            // Hidden some info we dont need it
            $project->makeHidden(['created_at', 'updated_at', 'deleted_at']);
            if (!$project) {
                throw new Exception('Error: Project not found');
            }
            return $project;
        }catch(Exception $e){
            Log::error('SomeThing Wrong when get project'.$e->getMessage());
            throw new Exception('There is an error in server');

        }
}

}

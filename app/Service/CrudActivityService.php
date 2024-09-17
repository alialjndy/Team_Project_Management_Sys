<?php
namespace App\Service;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Project_User;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class CrudActivityService{
    /**
     * Summary of getAllActivity
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllActivity(){
        $allActivities = Activity::all();
        return $allActivities;
    }
    /**
     * Summary of getActivityById
     * @param mixed $id
     * @throws \Exception
     */
    public function getActivityById($id){
        try{
            $activity = Activity::findOrFail($id);
            return $activity;
        }catch(Exception $e){
            Log::error('Error when get activty by id');
            throw new Exception('There is an error in server'.$e->getMessage());
        }
    }
    /**
     * Summary of createActivity
     * @param array $data
     * @throws \Exception
     */
    public function createActivity(array $data){
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->id ;
            if($data['user_id'] != $user_id){
                throw new Exception('User ID does not match the authenticated user.');
            }
            $activity = Activity::create($data);
            $record = Project_User::where('project_id', $data['project_id'])
                ->where('user_id', $user_id)
                ->first();

            $AllHoursInProject = Activity::where('project_id',$data['project_id'])
            ->where('user_id',$user_id)
            ->sum('duration_hours');
            if($record){
                $record->contribution_hours = $AllHoursInProject;
                $record->last_activity = $activity->created_at;
                $record->save();
            }else{
                throw new Exception('record not found');
            }
            return $activity;
        }catch(Exception $e){
            Log::error('Error when create activty'. $e->getMessage());
            throw new Exception('There is an error in server' .$e->getMessage());
        }
    }
    /**
     * Summary of updateActivity
     * @param array $data
     * @param mixed $id
     * @throws \Exception
     * @return void
     */
    public function updateActivity(array $data, $id){
        try{
            $activity = Activity::findOrFail($id);

            // Authenticate User by Token
            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->id;

            if($user_id !== $activity->user_id ){
                throw new Exception('User ID does not match the authenticated user.');
            }
            $activity->update($data);

            $record = Project_User::where('project_id', $activity->project_id)
                ->where('user_id',$user_id)
                ->first();
            if($record){
                $updateAllHours = Activity::where('project_id',$activity->project_id)
                    ->where('user_id',$user_id)
                    ->sum('duration_hours');

                $record->contribution_hours = $updateAllHours;
                $record->last_activity = $activity->created_at ;
                $record->save();
            }else{
                throw new Exception('record not found');
            }

            return $activity;
        }catch(Exception $e){
            Log::error('Error when get update activty');
            throw new Exception('There is an error in server'.$e->getMessage());
        }
    }
    /**
     * Summary of deleteActivity
     * @param mixed $id
     * @throws \Exception
     * @return void
     */
    public function deleteActivity($id){
        try{
            // Get Activity by ID
            $activity = Activity::findOrFail($id);

            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->id ;

            if($user_id != $activity->user_id){
                throw new Exception('User ID does not match the authenticated user.');
            }
            $activity->delete();
        }catch(Exception $e){
            Log::error('Error when get delete activity');
            throw new Exception('There is an error in server'.$e->getMessage());
        }
    }
}
?>

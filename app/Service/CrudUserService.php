<?php
namespace App\Service;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Catch_;

class CrudUserService{
    public function filter(Request $request){
        try{
            $query = User::query();
            if($request->has('role')){
                $query->where('role',$request->get('role'));
            }
            return $query->get();
        }
        catch(Exception $e){
            Log::error('Error in Filtering user'.$e->getMessage());
            throw new Exception('Error occured in the server');
        }

    }
    /**
     * Summary of createUser
     * @param array $data
     * @return
     */
    public function createUser(array $data){
        $user = User::create($data);
        return $user ;
    }
    public function updateUser(array $data, string $id){
        try{
            $user = User::findOrFail($id);
            $updateUser = $user->update($data);
            return $updateUser;
        }catch(Exception $e){
            Log::error('Error in Update User'.$e->getMessage());
            throw new Exception('There is an error in server');
        }
    }
    public function showUser($id){
        try{
            $user = User::find($id);
            return $user;
        }catch(Exception $e){
            Log::error('Error in Show User with ID'.$id.''.$e->getMessage());
            throw new Exception('There is an error in server');
        }
    }
    /**
     * Summary of softDelete
     * @param string $id
     * @throws \Exception
     * @return void
     */
    public function softDelete(string $id){
        try{
            $user = User::find($id);
            if(!$user){
                throw new Exception('User not found');
            }
            $user->delete();
        }catch(Exception $e){
            Log::error('Error in deleting user: ' . $e->getMessage());
            throw new Exception('There is an error in server');
        }
    }
    /**
     * Summary of restoreUser
     * @param mixed $id
     */
    public function restoreUser($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return $user;
    }
    /**
     * Summary of forceDelete
     * @param mixed $id
     * @return void
     */
    public function forceDelete($id){
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();
    }
    /**
     * Summary of AssignRoleToUserOnTheSystem
     * @param mixed $role
     * @param mixed $id
     * @throws \Exception
     */
    public function AssignRoleToUserOnTheSystem($role, $id){
        try{
            // Find User By Id
            $user = User::findOrFail($id);

            //Assign New Role to the user
            $user->role = $role ;
            $user->save();

            //Return user with new role
            return $user;
        }catch(Exception $e){// Throw new exception
            Log::error('Failed: Error in Assign Role To User On The System');
            throw new Exception('There is an error in the server'.$e->getMessage());
        }
    }
}
?>

<?php

namespace App\Http\Requests\Admin;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssignProjectToUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            if($user && $user->role =='manager'){
                return true;
            }else{
                abort(403,'Forbidden: You do not have access to this resource');
            }
        }catch(Exception $e){
            abort(500 , 'An error occurred: ' . $e->getMessage());
            throw new Exception('There is an error in server');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'project_id'=>'required|exists:projects,id',
            'user_id'=>'required|exists:users,id',
            'role'=>'required|in:manager,developer,tester',
        ];
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator){
        throw new HttpResponseException(response()->json([
            'status'=>'failed',
            'message'=>'Failed Verification please confirm the input',
            'error'=>$validator->errors()
        ],422));
    }
    public function attributes(){
        return [
            'user_id'=>'user ID',
            'role'=>'User Role'
        ];
    }
    public function messages(){
        return [
            'required' => 'The :attribute field is required.',
            'in'       => 'The selected :attribute must be one of the following: admin, manager, or member.',
            'exists'   => 'The selected :attribute must exist in the users table with a valid ID.'
        ];
    }

}

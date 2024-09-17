<?php

namespace App\Http\Requests\Admin;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            if($user && $user->role =='admin'){
                return true;
            }else{
                abort(403,'Forbidden: You do not have access to this resource');
            }
        }catch(Exception $e){
            abort(500 , 'An error occurred: ' . $e->getMessage());
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
        'name'=>'nullable|string|max:100|unique:projects,name',
        'description'=>'nullable|string',
        'start_date'=>'nullable|date',
        'end_date'=>'nullable|date',
        'status'=>'nullable|in:new,in_progress,completed,failed',
        'project_manager_id'=>'nullable|exists:users,id'
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
            'name'=>'Project Name',
            'description'=>'Project description',
            'start_date'=>'Start Date the project',
            'end_date'=>'End Date',
            'status'=>'Status of Project',
            'project_manager_id'=>'Project Manager ID'
        ];
    }
    public function messages(){
        return [
            'string'=>'The :attribute value must be a string',
            'unique'=>'The :attribute field must be a unique value',
            'in'=>'The Role must be in new,in_progress,completed,failed',
            'max'=>'The max length of this feild is 30 character',
            'date'=>'The :attribute feild must be a date',
            'exists'=>'The :attribute feild value does not exist in users (id)'
        ];
    }
}

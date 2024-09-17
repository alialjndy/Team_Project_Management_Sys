<?php

namespace App\Http\Requests\Task;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AddNoteToTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            if($user && $user->projects()->wherePivot('role','tester')->exists()){
                return true ;
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
            'observation'=>'required',
            'task_id'=>'required|exists:tasks,id',
            'project_id'=>'required|exists:projects,id'
        ];
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status'=>'failed',
                'message'=>'Failed verification please confirm the input',
                'errors'=>$validator->errors()
            ],401)
        );
    }
    public function attributes(){
        return [
            'task_id'=>'Task ID'
        ];
    }
    public function messages(){
        return [
            'required'=>'The :attribute field is required',
            'exists'=>'The :attribute feild value does not exist in (id)',
        ];
    }
}
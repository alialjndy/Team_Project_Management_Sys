<?php

namespace App\Http\Requests\Task;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class updateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            if($user && $user->projects()->wherePivot('role','manager')->exists()){
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
            'title'=>'nullable|string|max:100',
            'description'=>'nullable|string',
            'due_date'=>'nullable|date',
            'priority'=>'nullable|in:low,medium,high',
            'status'=>'nullable|in:pending,in_progress,done,failed',
            'to_assigned'=>'nullable|exists:users,id',
            'observation'=>'nullable|string'
        ];
    }
    /**
     * Summary of failedValidation
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return never
     */
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
    /**
     * Summary of attributes
     * @return string[]
     */
    public function attributes(){
        return [
            'project_id'=>'project ID',
            'manager_id'=>'Manager ID',
            'title' => 'Task Title',
            'description' => 'Task Description',
            'due_date' => 'Due Date',
            'priority' => 'Task Priority',
            'status' => 'Task Status',
            'to_assigned' => 'Assigned To',
            'observation'=>'Task observation'
        ];
    }
    /**
     * Summary of messages
     * @return string[]
     */
    public function messages()
    {
        return [
            'exists'=>'Invalid manager ID',
            'string'=>'The :attribute field must be a string',
            'max'=>'The :attribute field connot be longer than 100 characters',
            'priority.in' => 'The priority must be one of the following: low, medium, or high',
            'status.in' => 'The status must be one of the following: pending, in_progress, done, or failed'
        ];
    }
}
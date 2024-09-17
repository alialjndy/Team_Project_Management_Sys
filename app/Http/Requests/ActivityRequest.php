<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'required|exists:tasks,id',
            'content' => 'required|string',
            'duration_hours' => 'required|numeric|min:0',
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
            'user_id' => 'User ID',
            'project_id' => 'Project ID',
            'task_id' => 'Task ID',
            'content' => 'Content Activity',
            'duration_hours' => 'Duration of Activity'
        ];
    }
    public function messages(){
        return [
            'required'=>'The :attribute field is required',
            'string'=>'The :attribute value must be a string',
            'exists'=>'The :attribute feild value does not exist in users (id)',
            'numeric'=>'The :attribute field must be a numeric value',
            'min'=>'The contribution hours min value is 0'
        ];
    }
}

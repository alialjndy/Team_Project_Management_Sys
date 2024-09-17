<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatedActivityRequest extends FormRequest
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
     * Summary of rules
     * @return array
     */
    public function rules(): array
    {
        return [
            'project_id' => 'nullable|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'content' => 'nullable|string',
            'duration_hours' => 'nullable|numeric|min:0',
        ];
    }
    /**
     * Summary of failedValidation
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return never
     */
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator){
        throw new HttpResponseException(response()->json([
            'status'=>'failed',
            'message'=>'Failed Verification please confirm the input',
            'error'=>$validator->errors()
        ],422));
    }
    /**
     * Summary of attributes
     * @return string[]
     */
    public function attributes(){
       return [
            'project_id' => 'Project ID',
            'task_id' => 'Task ID',
            'content' => 'Content Activity',
            'duration_hours' => 'Duration of Activity'
        ];
    }
    /**
     * Summary of messages
     * @return string[]
     */
    public function messages(){
        return [
            'string'=>'The :attribute value must be a string',
            'exists'=>'The :attribute feild value does not exist in users (id)',
            'numeric'=>'The :attribute field must be a numeric value',
            'min'=>'The contribution hours min value is 0'
        ];
    }
}

<?php

namespace App\Http\Requests\Task;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class GetHighestPriorityRequest extends FormRequest
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
            'titleCondition'=>'required|string|max:100',
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
            'project_id'=>'Project ID',
            'titleCondition'=>'Title Condition'
        ];
    }
    public function messages(){
        return [
            'required'=>'The :attribute field is required',
            'exists'=>'The :attribute feild value does not exist in (id)',
        ];
    }
}
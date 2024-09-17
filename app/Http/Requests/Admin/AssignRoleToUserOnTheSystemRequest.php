<?php

namespace App\Http\Requests\Admin;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssignRoleToUserOnTheSystemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            if($user && $user->role == 'admin'){
                return true;
            }else{
                abort(403,'Forbidden: You do not have access to this resource');
            }
        }catch(Exception $e){
            abort(500 , 'An Error Occurred');
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
            'role'=>'required|in:admin,manager,member',
            'user_id'=>'required|exists:users,id'
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
            'role'=>'User Role'
        ];
    }
    public function messages(){
        return [
            'required' => 'The :attribute field is required.',
            'in'       => 'The selected :attribute must be one of the following: admin, manager, or member.',
        ];
    }
}

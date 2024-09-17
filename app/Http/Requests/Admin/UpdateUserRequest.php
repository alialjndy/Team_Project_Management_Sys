<?php

namespace App\Http\Requests\Admin;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'name'=>'nullable|string|unique:users,name|max:30',
            'email'=>'nullable|email|unique:users,email',
            'password'=>'nullable',
            'role'=>'nullable|in:admin,manager,member'
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
            'name'=>'User Name',
            'email'=>'User Email',
            'password'=>'User Password',
            'role'=>'User Role'
        ];
    }
    public function messages(){
        return [
            'string'=>'The :attribute value must be a string',
            'unique'=>'The :attribute field must be a unique value',
            'email'=>'Please input a valid email',
            'in'=>'The Role must be in admin , manager or member',
            'max'=>'The max length of this feild is 30 character'
        ];
    }
}

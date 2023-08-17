<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserUpdateRequest extends FormRequest
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
   public function authorize()
   {
       return true;
   }

   /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, mixed>
    */
   public function rules()
   {
       return [
           'full_name' => 'required',
           'email' => 'required'
       ];
   }

   public function failedValidation(Validator $validator)
   {
       throw new HttpResponseException(response()->json([
           'success'   => false,
           'message'   => 'Errors found',
           'data'      => $validator->errors()
       ]));
   }

   public function messages()
   {
       return [
           'full_name.required' => 'The Full Name field is a mandatory',
           'email.required' => 'The Email field is a mandatory'
       ];
   }
}

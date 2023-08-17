<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserRegisterRequest extends FormRequest
{
  public $validator = null;
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
      'full_name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string|min:8|confirmed',
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
      'email.required' => 'The Email field is a mandatory',
      'password.required' => 'The Password field is a numeric field'
    ];
  }
}

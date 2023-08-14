<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitorRequest extends FormRequest
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
            'name' => 'required|string|max:300',
            'nickname' => 'string|max:100',
            'gender_id' => 'uuid|exists:gender,id',
            'marital_status_id' => 'uuid|exists:marital_status,id',
            'date_birth' => 'date',
            'naturalness_id' => 'uuid|exists:naturalness,id',
            'profession' => 'string|max:200',
            'schooling_id' => 'uuid|exists:schooling,id',
            'rg' => 'integer',
            'cpf' => 'integer',
            'sus_card' => 'integer',
            'title' => 'string|max:200',
            'zona' => 'integer',
            'secao' => 'integer',
            'category_id' => 'uuid|exists:visitor_category,id',
            'has_children' => 'required|boolean',
            'mothers_name' => 'string|max:200',
            'photo_name' => 'string|max:300',
            'photo_path' => 'string|max:500',
            'photo_type' => 'string|max:250',
            'photo_extension' => 'string|max:50',
            'primary_email' => 'string|max:200|email',
            'secondary_email' => 'string|max:200|email|different:primary_email',
            'tag' => 'required|string|max:200',
            'department_id' => 'uuid|exists:department,id',
            'political_party_id' => 'uuid|exists:political_party,id',
            'entity_id' => 'uuid|exists:pgsql.entity_unity.unity,id'
        ];
    }
}

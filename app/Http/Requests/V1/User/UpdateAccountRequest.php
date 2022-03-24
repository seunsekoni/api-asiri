<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('users', 'email')->ignoreModel($this->user()),
            ],
            'phone' => [
                'nullable',
                'string',
                Rule::unique('users', 'phone')->ignoreModel($this->user()),
            ],
            'profile_picture' => 'nullable|image',
        ];
    }
}

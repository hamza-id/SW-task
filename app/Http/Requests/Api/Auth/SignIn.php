<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ValidationHelper;

class SignIn extends FormRequest
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
            'email'    => 'required|string|email|max:255',
            'password' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $validationHelper = new ValidationHelper();
        $validationHelper->failed($validator);
    }
}

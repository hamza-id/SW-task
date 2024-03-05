<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ValidationHelper;

class MovieUpdate extends FormRequest
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
            'title'         => 'sometimes|required|string',
            'director'      => 'sometimes|required|string',
            'release_date'  => 'sometimes|required|date',
            'episode_id'    => 'sometimes|required|integer',
            'opening_crawl' => 'nullable|string',
            'producer'      => 'nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $validationHelper = new ValidationHelper();
        $validationHelper->failed($validator);
    }
}

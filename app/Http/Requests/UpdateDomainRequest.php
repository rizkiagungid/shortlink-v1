<?php

namespace App\Http\Requests;

use App\Rules\ValidateExternalDomainNameRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDomainRequest extends FormRequest
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
            'index_page' => ['sometimes', 'nullable', 'url', 'max:255', new ValidateExternalDomainNameRule($this->name)],
            'not_found_page' => ['sometimes', 'nullable', 'url', 'max:255', new ValidateExternalDomainNameRule($this->name)]
        ];
    }
}

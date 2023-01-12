<?php

namespace App\Http\Requests;

use App\Rules\ValidateExtendedLicenseRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
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
            'name' => ['required', 'max:64', new ValidateExtendedLicenseRule()],
            'description' => ['required', 'max:256'],
            'amount_month' => ['required', 'numeric', 'gt:0', 'max:9999999999'],
            'amount_year' => ['required', 'numeric', 'gt:0', 'max:9999999999'],
            'currency' => ['required'],
            'coupons' => ['sometimes', 'nullable'],
            'tax_rates' => ['sometimes', 'nullable'],
            'trial_days' => ['required', 'integer', 'min:0', 'max:3650'],
            'visibility' => ['integer', 'between:0,1'],
            'position' => ['integer', 'min:0', 'max:4294967295'],
            'features.api' => ['integer', 'between:0,1']
        ];
    }
}

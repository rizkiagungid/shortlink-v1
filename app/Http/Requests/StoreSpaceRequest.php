<?php

namespace App\Http\Requests;

use App\Rules\SpaceLimitGateRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSpaceRequest extends FormRequest
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
            'name' => ['required', 'max:32', 'unique:spaces,name,null,id,user_id,'.$this->user()->id, new SpaceLimitGateRule($this->user())],
            'color' => ['nullable', 'integer', 'between:1,6']
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Rules\PixelLimitGateRule;
use App\Rules\ValidatePixelValueRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePixelRequest extends FormRequest
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
            'name' => ['required', 'max:32', 'unique:pixels,name,null,id,user_id,'.$this->user()->id, new PixelLimitGateRule($this->user())],
            'type' => ['required', 'in:' . implode(',', array_keys(config('pixels')))],
            'value' => ['required', 'alpha_dash', 'max:255', new ValidatePixelValueRule($this->input('type'))]
        ];
    }
}

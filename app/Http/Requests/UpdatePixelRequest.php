<?php

namespace App\Http\Requests;

use App\Models\Pixel;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePixelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // If the request is to edit a pixel as a specific user, and the user is not an admin
        if ($this->has('user_id') && $this->user()->role == 0) {
            return false;
        }

        // Check if the pixel to be edited exists under that user
        if ($this->has('user_id')) {
            Pixel::where([['id', '=', $this->route('id')], ['user_id', '=', $this->input('user_id')]])->firstOrFail();
        }

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
            'name' => ['sometimes', 'min:1', 'max:32', 'unique:pixels,name,'.$this->route('id').',id,user_id,'.($this->input('user_id') ?? $this->user()->id)],
            'type' => ['sometimes', 'in:' . implode(',', array_keys(config('pixels')))],
            'value' => ['sometimes', 'alpha_dash', 'max:255']
        ];
    }
}

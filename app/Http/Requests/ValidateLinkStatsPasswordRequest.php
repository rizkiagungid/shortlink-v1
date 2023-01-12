<?php

namespace App\Http\Requests;

use App\Models\Link;
use App\Rules\ValidateLinkStatsPasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class ValidateLinkStatsPasswordRequest extends FormRequest
{
    /**
     * @var
     */
    var $link;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->link = Link::where('id', $this->route('id'))->firstOrFail();

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
            'password' => ['required', new ValidateLinkStatsPasswordRule($this->link)]
        ];
    }
}

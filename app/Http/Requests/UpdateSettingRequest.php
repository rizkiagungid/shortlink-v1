<?php

namespace App\Http\Requests;

use App\Rules\ValidateExtendedLicenseRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
            'license_key' => ['sometimes', 'required'],
            'index' => ['sometimes', 'nullable', 'url'],
            'logo' => ['sometimes', 'image', 'max:2000'],
            'favicon' => ['sometimes', 'image', 'max:2000'],
            'theme' => ['sometimes', 'integer', 'between:0,1'],
            'stripe' => ['sometimes', 'required', 'integer', 'between:0,1', new ValidateExtendedLicenseRule()],
            'stripe_key' => ['sometimes', 'required_if:stripe,1'],
            'stripe_secret' => ['sometimes', 'required_if:stripe,1'],
            'stripe_wh_secret' => ['sometimes', 'required_if:stripe,1'],
            'paypal' => ['sometimes', 'required', 'integer', 'between:0,1', new ValidateExtendedLicenseRule()],
            'paypal_mode' => ['sometimes', 'required_if:paypal,1'],
            'paypal_client_id' => ['sometimes', 'required_if:paypal,1'],
            'paypal_secret' => ['sometimes', 'required_if:paypal,1'],
            'paypal_webhook_id' => ['sometimes', 'required_if:paypal,1'],
            'coinbase' => ['sometimes', 'required', 'integer', 'between:0,1', new ValidateExtendedLicenseRule()],
            'coinbase_key' => ['sometimes', 'required_if:coinbase,1'],
            'coinbase_wh_secret' => ['sometimes', 'required_if:coinbase,1'],
            'bank' => ['sometimes', 'required', 'integer', 'between:0,1', new ValidateExtendedLicenseRule()],
            'social_facebook' => ['sometimes', 'nullable', 'url'],
            'social_twitter' => ['sometimes', 'nullable', 'url'],
            'social_instagram' => ['sometimes', 'nullable', 'url'],
            'social_youtube' => ['sometimes', 'nullable', 'url'],
            'webhook_user_created_url' => ['sometimes', 'nullable', 'url'],
            'webhook_user_deleted_url' => ['sometimes', 'nullable', 'url']
        ];
    }
}

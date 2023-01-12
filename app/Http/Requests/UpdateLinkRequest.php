<?php

namespace App\Http\Requests;

use App\Models\Link;
use App\Rules\LinkDisabledGateRule;
use App\Rules\LinkDomainGateRule;
use App\Rules\LinkExpirationGateRule;
use App\Rules\LinkPixelGateRule;
use App\Rules\LinkTargetingGateRule;
use App\Rules\LinkPasswordGateRule;
use App\Rules\LinkSpaceGateRule;
use App\Rules\ValidateAliasRule;
use App\Rules\ValidateBadWordsRule;
use App\Rules\ValidateDeepLinkRule;
use App\Rules\ValidateCountryKeyRule;
use App\Rules\ValidateDomainOwnershipRule;
use App\Rules\ValidateGoogleSafeBrowsingRule;
use App\Rules\ValidateLanguageKeyRule;
use App\Rules\ValidatePixelOwnersipRule;
use App\Rules\ValidatePlatformKeyRule;
use App\Rules\ValidateSpaceOwnershipRule;
use App\Rules\ValidateUrlRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // If the request is to edit a link as a specific user, and the user is not an admin
        if ($this->has('user_id') && $this->user()->role == 0) {
            return false;
        }

        // Check if the link to be edited exists under that user
        if ($this->has('user_id')) {
            Link::where([['id', '=', $this->route('id')], ['user_id', '=', $this->input('user_id')]])->firstOrFail();
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
            'url' => ['bail', 'sometimes', 'required', new ValidateUrlRule(), 'max:2048', new ValidateBadWordsRule(), new ValidateDeepLinkRule($this->user()), new ValidateGoogleSafeBrowsingRule()],
            'alias' => ['sometimes', 'alpha_dash', 'max:255', new ValidateAliasRule(), new ValidateBadWordsRule()],
            'password' => ['sometimes', 'nullable', 'string', 'min:1', 'max:128', new LinkPasswordGateRule($this->user())],
            'space' => ['nullable', 'integer', new ValidateSpaceOwnershipRule($this->input('user_id') ?? $this->user()->id), new LinkSpaceGateRule($this->user())],
            'pixels' => ['nullable', new ValidatePixelOwnersipRule($this->input('user_id') ?? $this->user()->id), new LinkPixelGateRule($this->user())],
            'disabled' => ['nullable', 'boolean', new LinkDisabledGateRule($this->user())],
            'privacy' => ['nullable', 'integer', 'between:0,2'],
            'privacy_password' => [(in_array($this->input('privacy'), [0, 1]) ? 'nullable' : 'sometimes'), 'string', 'min:1', 'max:128'],
            'expiration_url' => ['bail', 'nullable', new ValidateUrlRule(), 'max:2048', new ValidateBadWordsRule(), new LinkExpirationGateRule($this->user()), new ValidateDeepLinkRule($this->user()), new ValidateGoogleSafeBrowsingRule()],
            'expiration_date' => ['nullable', 'required_with:expiration_time', 'date_format:Y-m-d', new LinkExpirationGateRule($this->user())],
            'expiration_time' => ['nullable', 'required_with:expiration_date', 'date_format:H:i', new LinkExpirationGateRule($this->user())],
            'expiration_clicks' => ['nullable', 'integer', 'min:0', 'digits_between:0,9', new LinkExpirationGateRule($this->user())],
            'target_type' => ['nullable', 'integer', 'min:0', 'max:4'],
            'country.*.key' => ['nullable', 'required_with:country.*.value', new ValidateCountryKeyRule(), new LinkTargetingGateRule($this->user())],
            'country.*.value' => ['bail', 'nullable', 'required_with:country.*.key', 'max:2048', new ValidateUrlRule(), new ValidateBadWordsRule(), new ValidateDeepLinkRule($this->user())],
            'platform.*.key' => ['nullable', 'required_with:platform.*.value', new ValidatePlatformKeyRule(), new LinkTargetingGateRule($this->user())],
            'platform.*.value' => ['bail', 'nullable', 'required_with:platform.*.key', 'max:2048', new ValidateUrlRule(), new ValidateBadWordsRule(), new ValidateDeepLinkRule($this->user())],
            'language.*.key' => ['nullable', 'required_with:language.*.value', new ValidateLanguageKeyRule(), new LinkTargetingGateRule($this->user())],
            'language.*.value' => ['bail', 'nullable', 'required_with:language.*.key', 'max:2048', new ValidateUrlRule(), new ValidateBadWordsRule(), new ValidateDeepLinkRule($this->user())],
            'rotation.*.value' => ['bail', 'nullable', 'max:2048', new ValidateUrlRule(), new ValidateBadWordsRule(), new ValidateDeepLinkRule($this->user()), new LinkTargetingGateRule($this->user())]
        ];
    }

    public function attributes()
    {
        return [
            'url' => __('Link'),
            'urls' => __('Links'),
            'alias' => __('Alias'),
            'password' => __('Password'),
            'space' => __('Space'),
            'domain' => __('Domain'),
            'pixels' => __('Pixels'),
            'disabled' => __('Disabled'),
            'privacy' => __('Stats'),
            'privacy_password' => __('Password'),
            'expiration_url' => __('Expiration link'),
            'expiration_date' => __('Expiration date'),
            'expiration_time' => __('Expiration time'),
            'expiration_clicks' => __('Expiration clicks'),
            'country.*.key' => __('Country'),
            'country.*.value' => __('Link'),
            'platform.*.key' => __('Platform'),
            'platform.*.value' => __('Link'),
            'language.*.key' => __('Language'),
            'language.*.value' => __('Link'),
            'rotation.*.value' => __('Link')
        ];
    }
}

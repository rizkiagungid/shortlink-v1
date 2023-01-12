<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateExternalDomainNameRule implements Rule
{
    /**
     * @var
     */
    var $domain;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $domain = parse_url($this->domain)['host'] ?? $this->domain;

        // If the domain is the same with the installation URL
        if ($domain == parse_url(config('app.url'))['host']) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :attribute is invalid.');
    }
}

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateDnsRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        // If the domain is the same with the installation URL
        if ($value == parse_url(config('app.url'))['host']) {
            return true;
        }

        // Check if the remote host points to the local host
        if (getRemoteIp($value) == getHostIp()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The DNS A record does not point to our server, or the DNS did not propagated yet, this can take up to 24 hours.');
    }
}

<?php

namespace App\Rules;

use App\Models\Domain;
use Illuminate\Contracts\Validation\Rule;

class ValidateGuestDomainRule implements Rule
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
        // Check if the custom domain exists and is the same with the default one
        // Or if the default and selected domains are empty
        if (Domain::where([['user_id', '=', 0], ['id', '=', $value]])->exists() && config('settings.short_domain') == $value || empty(config('settings.short_domain')) && empty($value)) {
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
        return __('Something went wrong, please try again.');
    }
}

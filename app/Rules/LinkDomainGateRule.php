<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LinkDomainGateRule implements Rule
{
    /**
     * @var
     */
    private $user;

    /**
     * Create a new rule instance.
     *
     * @param $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        if ($this->user->can('domains', ['App\Models\Link']) || $this->user->can('globalDomains', ['App\Models\Link']) || (config('settings.short_domain') && config('settings.short_domain') == $value)) {
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
        return __('You don\'t have access to this feature.');
    }
}

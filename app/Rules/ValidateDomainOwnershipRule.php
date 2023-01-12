<?php

namespace App\Rules;

use App\Models\Domain;
use Illuminate\Contracts\Validation\Rule;

class ValidateDomainOwnershipRule implements Rule
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
        // Check if the domain is owned by the user
        // or if the domain is part of the global domains, and the user has access to them
        // or if there's a default global domain, and is the same as the selected domain
        if (
            Domain::where([['id', '=', $value], ['user_id', '=', $this->user->id]])
            ->when(($this->user->can('globalDomains', ['App\Models\Link']) || (config('settings.short_domain') && config('settings.short_domain') == $value)), function ($query) use ($value) {
                return $query->orWhere([['id', '=', $value], ['user_id', '=', 0]]);
            })
            ->exists()
        ) {
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
        return __('Invalid value.');
    }
}

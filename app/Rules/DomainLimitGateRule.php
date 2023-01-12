<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DomainLimitGateRule implements Rule
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
        if (request()->is('admin/*') && $this->user->role == 1) {
            return true;
        }

        if ($this->user->can('create', ['App\Models\Domain'])) {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('You added too many domains.');
    }
}

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ValidateDeepLinkRule implements Rule
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
        $url = parse_url($value);

        // If the URL is not a website link
        if (isset($url['scheme']) && $url['scheme'] != 'http' && $url['scheme'] != 'https') {
            if (!Auth::check()) {
                return false;
            }

            if ($this->user->cannot('deepLinks', ['App\Models\Link'])) {
                return false;
            }
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
        return __('You don\'t have access to this feature.');
    }
}

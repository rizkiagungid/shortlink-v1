<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateUrlsCountRule implements Rule
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
        $this->attribute = $attribute;

        if (count(preg_split('/\n|\r/', $value, -1, PREG_SPLIT_NO_EMPTY)) > config('settings.short_max_multi_links')) {
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
        return __('The number of :attribute must not exceed :value.', ['attribute' => $this->attribute, 'value' => config('settings.short_max_multi_links')]);
    }
}

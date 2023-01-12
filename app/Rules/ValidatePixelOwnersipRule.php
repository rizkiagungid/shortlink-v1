<?php

namespace App\Rules;

use App\Models\Pixel;
use Illuminate\Contracts\Validation\Rule;

class ValidatePixelOwnersipRule implements Rule
{
    /**
     * @var
     */
    private $userId;

    /**
     * Create a new rule instance.
     *
     * @param $userId
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
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
        if (empty(array_filter($value))) {
            return true;
        }

        if (Pixel::where('user_id', '=', $this->userId)->whereIn('id', array_filter($value))->exists()) {
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

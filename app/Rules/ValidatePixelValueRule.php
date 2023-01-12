<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidatePixelValueRule implements Rule
{
    /**
     * The pixel type
     *
     * @var
     */
    private $type;

    /**
     * The input attribute
     *
     * @var
     */
    private $attribute;

    /**
     * The expected value format
     *
     * @var
     */
    private $format;

    /**
     * Create a new rule instance.
     *
     * @param $type
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
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

        if ($this->type == 'adroll') {
            if (mb_strpos($value, '-') == false) {
                $this->format = 'ADVID-PIXID';
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
        return __('The :attribute must be in :format format.', ['attribute' => $this->attribute, 'format' => $this->format]);
    }
}

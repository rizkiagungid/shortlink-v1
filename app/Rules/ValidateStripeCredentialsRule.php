<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateStripeCredentialsRule implements Rule
{
    /**
     * @var
     */
    private $message;

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
        try {
            \Stripe\Stripe::setApiKey($value);

            \Stripe\Token::retrieve(
                'validate_credentials'
            );
        } catch (\Stripe\Exception\AuthenticationException $e) {
            $this->message = $e->getMessage();

            return false;
        } catch (\Exception $e) {
            return true;
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
        return $this->message;
    }
}

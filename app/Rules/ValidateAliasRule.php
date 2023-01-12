<?php

namespace App\Rules;

use App\Models\Link;
use Illuminate\Contracts\Validation\Rule;

class ValidateAliasRule implements Rule
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
        $conditions = [];

        $conditions[] = ['alias', '=', $value];

        // If the query is for a specific link
        if (request()->route('id')) {
            // Exclude the link when validating the alias
            $conditions[] = ['id', '!=', request()->route('id')];

            $link = Link::findOrFail(request()->route('id'));
            $conditions[] = ['domain_id', '=', $link->domain->id ?? null];
        } else {
            // If the request has a link under a domain
            if (request()->input('domain')) {
                $conditions[] = ['domain_id', '=', request()->input('domain')];
            }
            // Check for links that are not under a domain
            else {
                $conditions[] = ['domain_id', '=', null];
            }
        }

        if (Link::where($conditions)->exists()) {
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
        return __('validation.unique');
    }
}

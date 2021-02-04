<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class alphaNumString implements Rule
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
        if (!is_string($value))
            return false;

        $value = str_replace('-', '', $value);
        $value = str_replace('_', '', $value);
        return ctype_alnum($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __(":attribute darf nur aus Buchstaben, Zahlen, Unterstrichen und Bindestrichen bestehen!");
    }
}

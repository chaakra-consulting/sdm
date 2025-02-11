<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class TimeFormat implements ValidationRule
{
    /**
     * Validate the attribute.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (\DateTime::createFromFormat('H:i', $value) !== false) {
            return;
        }

        if (\DateTime::createFromFormat('H:i:s', $value) !== false) {
            return;
        }

        $fail("The $attribute must be in format H:i or H:i:s.");
    }
}

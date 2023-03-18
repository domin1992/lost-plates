<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailOrPhoneNumberValidationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (
            !preg_match("/^(?:\(?\+?48)?(?:[-\.\(\)\s]*(\d)){9}\)?$/", $value)
            && !filter_var($value, FILTER_VALIDATE_EMAIL)
        ) {
            $fail('Nieprawidłowy format adresu e-mail lub numeru telefonu.');
        }
    }
}

<?php

namespace App\Rules;

use App\Services\VATnumber;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VAT implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $api = new VATnumber();

        if(!$api->verify($value, 'FR')) {
            $fail('VAT number is not correct');
        }
    }
}

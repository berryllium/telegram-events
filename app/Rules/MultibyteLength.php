<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MultibyteLength implements ValidationRule
{
    public function __construct(
        private $max
    ){}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(mb_strlen($value) > $this->max) {
            $fail('validation.max.string')->translate(['max' => $this->max]);
        }
    }
}

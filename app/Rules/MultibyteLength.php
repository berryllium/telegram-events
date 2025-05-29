<?php

namespace App\Rules;

use App\Models\Message;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MultibyteLength implements ValidationRule
{
    public function __construct(
        private $max,
        private $extra_text = '',
    ){}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value .= $this->extra_text;
        $value = Message::stripTextEditorTags($value);

        if(mb_strlen($value) - $this->max > 0) {
            $fail('validation.max.string_excess')->translate([
                'max' => $this->max,
                'excess' => mb_strlen($value) - $this->max
            ]);
        }
    }
}

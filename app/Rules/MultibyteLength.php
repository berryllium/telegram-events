<?php

namespace App\Rules;

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
        // TODO вынести очистку тегов в отдельный метод и применить его тут и в классе Message
        $value .= $this->extra_text;
        $value = preg_replace(
            ['/<p>/', '/<\/p>/', '/<br>/i', '/<strong>/', '/<\/strong>/'],
            ['', "\r\n", "\r\n", '<b>', '</b>'],
            $value
        );
        $value = trim($value, "&nbsp;\r\n");
        $value = str_replace('&nbsp;', "\r\n", $value);

        if(mb_strlen($value) - $this->max > 0) {
            $fail('validation.max.string_excess')->translate([
                'max' => $this->max,
                'excess' => mb_strlen($value) - $this->max
            ]);
        }
    }
}

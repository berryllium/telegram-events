<?php

namespace App\Rules;

use Closure;
use DOMDocument;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidMessage implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadHTML($value);

        if(libxml_get_errors()) {
            libxml_use_internal_errors(false);
            $fail('Невалидный html в сообщении');
        }

        $elements = $dom->getElementsByTagName('*');
        foreach ($elements as $element) {
            if (!in_array($element->tagName, ['html', 'body', 'b', 'i', 'code'])) {
                $fail('Запрещенные html теги в сообщении - ' . $element->tagName);
            }
        }
    }
}

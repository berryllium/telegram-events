<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Form>
 */
class FormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $template = <<<TEMPLATE
<code>{{ \$place }}</code>
<i>{{ \$address }}</i>
TEMPLATE;

        return [
            'name' => __('webapp.form') . ' ' . fake()->numberBetween(1, 500),
            'description' => fake()->realText(),
            'template' => $template,
        ];
    }
}

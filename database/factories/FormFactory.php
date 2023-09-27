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
<b>{{ \$string }}</b>
<code>{{ \$place }}</code>
<i>{{ \$address }}</i>
TEMPLATE;

        return [
            'name' => __('webapp.form') . ' ' . fake()->numberBetween(1, 500),
            'description' => fake()->realText(),
            'template' => $template,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Form $form) {
            foreach(array_keys(Field::$types) as $type) {
                $arr = [
                    'name' => $type,
                    'code' => $type,
                    'type' => $type,
                ];
                if($type == 'select') {
                    $arr['dictionary_id'] = 1;
                }
                $form->fields()->create($arr);
            }
        });
    }
}

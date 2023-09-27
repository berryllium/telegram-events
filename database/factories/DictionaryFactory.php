<?php

namespace Database\Factories;

use App\Models\Dictionary;
use App\Models\Field;
use App\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Form>
 */
class DictionaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->realText(10),
            'description' => fake()->realText(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Dictionary $dictionary) {
            foreach(['0+', '6+', '12+', '16+', '18+'] as $age) {
                $dictionary->dictionary_values()->create([
                    'value' => $age,
                ]);
            }
        });
    }
}

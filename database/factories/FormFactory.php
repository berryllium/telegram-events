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
        return [
            'name' => fake()->word(),
            'description' => fake()->text(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Form $form) {
            for($i = 0; $i < fake()->numberBetween(3, 8); $i++) {
                $form->fields()->create([
                    'name' => fake()->word(),
                    'type' => fake()->randomElement(array_keys(Field::$types)),
                ]);
            }
        });
    }
}

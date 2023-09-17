<?php

namespace Database\Factories;

use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TelegramChannel>
 */
class PlaceFactory extends Factory
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
            'address' => fake()->address(),
            'description' => fake()->realText(),
            'form_id' => 1
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Place $place) {
            $place->telegram_channels()->sync([1]);
        });
    }
}

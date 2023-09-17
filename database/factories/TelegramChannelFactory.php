<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TelegramChannel>
 */
class TelegramChannelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tg_id' => fake()->numberBetween(),
            'name' => fake()->title(),
            'description' => fake()->realText()
        ];
    }
}

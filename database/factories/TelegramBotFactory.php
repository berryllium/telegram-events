<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TelegramChannel>
 */
class TelegramBotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Bot ' . fake()->numberBetween(1, 100),
            'code' => fake()->uuid(),
            'api_token' => fake()->iosMobileToken,
            'description' => fake()->text(),
            'moderation_group' => fake()->numberBetween(1, 1000000),
            'form_id' => 1
        ];
    }
}

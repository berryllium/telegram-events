<?php

namespace Database\Seeders;

use App\Models\Dictionary;
use Illuminate\Database\Seeder;

class DictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dictionary::factory()->create(
            [
                'name' => __('webapp.age'),
                'description' => fake()->realText(),
                'telegram_bot_id' => 1
            ]
        );
    }
}

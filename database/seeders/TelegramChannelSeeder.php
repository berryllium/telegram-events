<?php

namespace Database\Seeders;

use App\Models\TelegramChannel;
use Illuminate\Database\Seeder;

class TelegramChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TelegramChannel::factory()->create([
            'tg_id' => 6043059350,
            'name' => 'Test Channel',
            'description' => 'Test channel to check functionality',
            'form_id' => 1
        ]);
    }
}

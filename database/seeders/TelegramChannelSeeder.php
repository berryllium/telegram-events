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
            'tg_id' =>  -1001690629442,
            'name' => 'Test Channel',
            'description' => 'Test channel to check functionality',
        ]);
    }
}

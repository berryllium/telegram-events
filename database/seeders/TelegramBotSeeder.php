<?php

namespace Database\Seeders;

use App\Models\TelegramBot;
use App\Models\Channel;
use Illuminate\Database\Seeder;

class TelegramBotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TelegramBot::factory()->create([
            'name' => 'My Bot',
            'code' => 'dimabot',
            'api_token' => config('app.service_bot.token'),
            'moderation_group' => config('app.service_bot.channel'),
            'description' => 'My bot',
            'form_id' => 1
        ]);
    }
}

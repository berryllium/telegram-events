<?php

namespace Database\Seeders;

use App\Models\TelegramBot;
use App\Models\TelegramChannel;
use Illuminate\Database\Seeder;

class TelegramBotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TelegramBot::factory()->create([
            'name' => 'Мой бот',
            'code' => 'dimabot',
            'api_token' => '6693099766:AAF45rcSrSzvUapg7IUpazpkIKhUABbUwho',
            'moderation_group' => 168827230,
            'description' => 'My bot',
            'form_id' => 1
        ]);
    }
}
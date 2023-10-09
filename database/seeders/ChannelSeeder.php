<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Channel::factory()->create([
            'tg_id' =>  -1001690629442,
            'name' => 'Test Channel',
            'description' => 'Test channel to check functionality',
        ]);
    }
}

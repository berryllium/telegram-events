<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\TelegramChannel;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::factory()->create([
            'name' => 'supervisor',
            'title' => 'supervisor',
        ]);
        Role::factory()->create([
            'name' => 'admin',
            'title' => 'admin',
        ]);
        Role::factory()->create([
            'name' => 'moderator',
            'title' => 'moderator',
        ]);
    }
}

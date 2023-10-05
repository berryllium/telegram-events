<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supervisor = User::factory()->create([
            'name' => 'Supervisor',
            'email' => 'supervisor@test.com',
            'password' => Hash::make('qwer1234')
        ]);
        $supervisor->roles()->attach(Role::query()->where('name', '=', 'supervisor')->first());
        $supervisor->telegram_bots()->attach(1);


        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('qwer1234')
        ]);
        $admin->roles()->attach(Role::query()->where('name', '=', 'admin')->first());
        $admin->telegram_bots()->attach(1);


        $moderator = User::factory()->create([
            'name' => 'Moderator',
            'email' => 'moderator@test.com',
            'password' => Hash::make('qwer1234')
        ]);
        $moderator->roles()->attach(Role::query()->where('name', '=', 'moderator')->first());
        $moderator->telegram_bots()->attach(1);

    }
}

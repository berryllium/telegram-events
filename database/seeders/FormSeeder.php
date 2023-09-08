<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Form::factory()->create([
            'name' => 'Тестовая форма',
            'description' => 'Форма для проверки функционала'
        ]);
    }
}

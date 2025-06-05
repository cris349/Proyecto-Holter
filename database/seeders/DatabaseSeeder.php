<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->count(50)->create();
        \App\Models\Pacientes::factory()->count(100)->create();
        \App\Models\Dispositivos::factory()->count(10)->create();
        \App\Models\Especialistas::factory()->count(10)->create();
        \App\Models\Procedimientos::factory()->count(60)->create();
    }
}

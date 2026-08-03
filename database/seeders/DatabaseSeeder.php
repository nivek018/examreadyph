<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@examreadyph.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@examreadyph.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Seed system settings
        $this->call(SystemSettingSeeder::class);

        // Seed exam data
        $this->call(ExamSeeder::class);

        // Seed subscription pricing plans
        $this->call(SubscriptionPlanSeeder::class);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),  // ✅ Add password!
        ]);

        // Run all seeders
        $this->call([
            AssessmentSeeder::class,
            QuestionSeeder::class,
            SkillSeeder::class,
        ]);
    }
}

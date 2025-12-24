<?php
namespace Database\Seeders;

use App\Models\Assessment;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        Assessment::create([
            'name' => 'Backend Developer Assessment',
            'description' => '20 MCQs - 15 minutes timer',
            'threshold_score' => 50,
            'duration_minutes' => 15,
            'pass_percentage' => 60,
            'is_active' => true,
        ]);
    }
}

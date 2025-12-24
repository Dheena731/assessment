<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            ['name' => 'Python', 'normalized_name' => 'python', 'weight' => 30],
            ['name' => 'Laravel', 'normalized_name' => 'laravel', 'weight' => 25],
            ['name' => 'Node.js', 'normalized_name' => 'node.js', 'weight' => 25],
            ['name' => 'JavaScript', 'normalized_name' => 'javascript', 'weight' => 25],
            ['name' => 'SQL', 'normalized_name' => 'sql', 'weight' => 20],
            ['name' => 'React', 'normalized_name' => 'react', 'weight' => 25],
            ['name' => 'Vue.js', 'normalized_name' => 'vue.js', 'weight' => 20],
            ['name' => 'HTML', 'normalized_name' => 'html', 'weight' => 15],
            ['name' => 'CSS', 'normalized_name' => 'css', 'weight' => 15],
            ['name' => 'Docker', 'normalized_name' => 'docker', 'weight' => 20],
            ['name' => 'AWS', 'normalized_name' => 'aws', 'weight' => 20],
            ['name' => 'Git', 'normalized_name' => 'git', 'weight' => 15],
            ['name' => 'MySQL', 'normalized_name' => 'mysql', 'weight' => 18],
            ['name' => 'PostgreSQL', 'normalized_name' => 'postgresql', 'weight' => 18],
            ['name' => 'MongoDB', 'normalized_name' => 'mongodb', 'weight' => 18],
            ['name' => 'Machine Learning', 'normalized_name' => 'machine learning', 'weight' => 30],
            ['name' => 'Data Analysis', 'normalized_name' => 'data analysis', 'weight' => 25],
            ['name' => 'PHPUnit', 'normalized_name' => 'phpunit', 'weight' => 15],
            ['name' => 'Postman', 'normalized_name' => 'postman', 'weight' => 12],
            ['name' => 'API', 'normalized_name' => 'api', 'weight' => 15],
        ];

        Skill::insert($skills);
    }
}

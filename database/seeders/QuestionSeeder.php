<?php
namespace Database\Seeders;

use App\Models\Question;
use App\Models\Option;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            // SQL (8 questions)
            ['question' => 'What does SQL stand for?', 'skill' => 'sql'],
            ['question' => 'Primary key constraint?', 'skill' => 'sql'],
            ['question' => 'JOIN types in SQL?', 'skill' => 'sql'],
            ['question' => 'GROUP BY purpose?', 'skill' => 'sql'],
            ['question' => 'INDEX improves?', 'skill' => 'sql'],
            ['question' => 'Foreign key does?', 'skill' => 'sql'],
            ['question' => 'SQL injection prevention?', 'skill' => 'sql'],
            ['question' => 'HAVING vs WHERE?', 'skill' => 'sql'],

            // Python (6 questions)  
            ['question' => 'Python uses _____ for lists?', 'skill' => 'python'],
            ['question' => 'Python virtual environment?', 'skill' => 'python'],
            ['question' => "'def' keyword for?", 'skill' => 'python'],
            ['question' => 'List comprehension syntax?', 'skill' => 'python'],
            ['question' => 'Python exception handling?', 'skill' => 'python'],
            ['question' => 'pip install does?', 'skill' => 'python'],

            // Docker (5 questions)
            ['question' => 'Docker command to run container?', 'skill' => 'docker'],
            ['question' => 'Dockerfile FROM instruction?', 'skill' => 'docker'],
            ['question' => 'docker-compose purpose?', 'skill' => 'docker'],
            ['question' => 'Docker volumes for?', 'skill' => 'docker'],
            ['question' => 'docker ps shows?', 'skill' => 'docker'],

            // Laravel (5 questions)
            ['question' => 'Laravel uses Eloquent for?', 'skill' => 'laravel'],
            ['question' => 'Laravel migrations for?', 'skill' => 'laravel'],
            ['question' => "'php artisan migrate' does?", 'skill' => 'laravel'],
            ['question' => 'Laravel middleware purpose?', 'skill' => 'laravel'],
            ['question' => 'Laravel routes in?', 'skill' => 'laravel'],

            // Git (4 questions)
            ['question' => 'Git clone command?', 'skill' => 'git'],
            ['question' => 'Git commit -m does?', 'skill' => 'git'],
            ['question' => 'Git branch create?', 'skill' => 'git'],
            ['question' => 'Git merge conflicts?', 'skill' => 'git'],

            // Linux (2 questions)
            ['question' => 'Linux chmod 755 means?', 'skill' => 'linux'],
            ['question' => 'ps aux shows?', 'skill' => 'linux']
        ];

        foreach ($questions as $index => $qData) {
            $question = Question::create([
                'question' => $qData['question'],
                'type' => 'mcq',
                'hint' => 'Think ' . $qData['skill'],
                'skill' => $qData['skill']
            ]);
            
            $options = $this->getOptions($index, $qData['skill']);
            
            foreach ($options as $order => $opt) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $opt[0],
                    'is_correct' => $opt[1],
                    'order' => $order + 1
                ]);
            }
        }
    }

    private function getOptions($index, $skill)
    {
        $options = [
            // SQL
            0 => [['Structured Query Language', true], ['Simple Query Language', false], ['Sequential Query Language', false], ['Standard Query Language', false]],
            1 => [['Unique identifier', true], ['Duplicate allowed', false], ['Text only', false], ['Date only', false]],
            2 => [['INNER JOIN', true], ['LEFT JOIN', false], ['RIGHT JOIN', false], ['FULL JOIN', false]],
            3 => [['Aggregate functions', true], ['Sort data', false], ['Filter rows', false], ['Update data', false]],
            4 => [['Query speed', true], ['Storage size', false], ['Backup speed', false], ['Insert speed', false]],
            5 => [['References another table', true], ['Unique values', false], ['Auto increment', false], ['Not null', false]],
            6 => [['Prepared statements', true], ['SHA256 hash', false], ['Input sanitization', false], ['All of above', false]],
            7 => [['HAVING: groups, WHERE: rows', true], ['WHERE: groups', false], ['Both same', false], ['HAVING optional', false]],

            // Python
            8 => [['Square brackets []', true], ['Curly braces {}', false], ['Parentheses ()', false], ['Angle brackets <>', false]],
            9 => [['virtualenv', true], ['pipenv', false], ['conda', false], ['pyenv', false]],
            10 => [['Functions', true], ['Classes', false], ['Variables', false], ['Loops', false]],
            11 => [['[x*2 for x in lst]', true], ['{x*2 for x in lst}', false], ['(x*2 for x in lst)', false], ['[x*2 in lst]', false]],
            12 => [['try/except', true], ['if/else', false], ['while/break', false], ['for/continue', false]],
            13 => [['Install packages', true], ['Create project', false], ['Run tests', false], ['Build docs', false]],

            // Docker
            14 => [['docker run', true], ['docker start', false], ['docker build', false], ['docker exec', false]],
            15 => [['Base image', true], ['Working directory', false], ['Copy files', false], ['Run command', false]],
            16 => [['Multi-container apps', true], ['Single container', false], ['Image build', false], ['Registry push', false]],
            17 => [['Persistent data', true], ['Network config', false], ['Environment vars', false], ['CPU limits', false]],
            18 => [['Running containers', true], ['Stopped containers', false], ['Images', false], ['Volumes', false]],

            // Laravel
            19 => [['Database ORM', true], ['Authentication', false], ['Routing', false], ['Validation', false]],
            20 => [['Schema changes', true], ['Controllers', false], ['Views', false], ['Models', false]],
            21 => [['Run migrations', true], ['Clear cache', false], ['Create model', false], ['Seed DB', false]],
            22 => [['Request filtering', true], ['Database queries', false], ['View rendering', false], ['Email sending', false]],
            23 => [['routes/web.php', true], ['app/Http/Controllers', false], ['resources/views', false], ['config/app.php', false]],

            // Git
            24 => [['Copy remote repo', true], ['Create branch', false], ['Merge changes', false], ['Push code', false]],
            25 => [['Commit message', true], ['Amend commit', false], ['Stash changes', false], ['Cherry pick', false]],
            26 => [['git branch new-branch', true], ['git checkout -b', false], ['git switch', false], ['git create', false]],
            27 => [['Edit conflicting files', true], ['git reset --hard', false], ['git rebase', false], ['git cherry-pick', false]],

            // Linux
            28 => [['Owner:rwx, Group:rx, Others:rx', true], ['All read/write', false], ['Owner:read, Others:write', false], ['Execute only', false]],
            29 => [['All processes', true], ['Current user', false], ['System services', false], ['Cron jobs', false]]
        ];

        return $options[$index] ?? $options[0];
    }
}

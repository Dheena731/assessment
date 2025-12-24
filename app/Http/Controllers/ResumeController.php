<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\Skill;
use App\Models\UserAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ResumeController extends Controller
{
    public function store(Request $request)
    {
        // ✅ Validate PDF (Day 2 spec)
        $request->validate([
            'resume' => 'required|file|mimes:pdf|max:2048',
        ]);

        // ✅ Store file
        $path = $request->file('resume')->store('resumes', 'local');

        // ✅ Create DB record
        $resume = Resume::create([
            'user_id' => auth()->id(),
            'file_path' => $path,
        ]);

        $filePath = Storage::disk('local')->path($path);

        $response = Http::timeout(30)
            ->attach(
                'resume',
                fopen($filePath, 'r'),
                basename($filePath)
            )
            ->post('http://host.docker.internal:8002/parse-resume');
            
        if ($response->failed()) {
            return back()->withErrors('Resume parsing failed.');
        }

        $data = $response->json();
        $text = $data['text'] ?? '';
        
        // ✅ Skills matching + scoring (Enhanced Day 2)
        $result = $this->calculateSkillScore($text);
        
        $resume->update([
            'parsed_text' => $text,
            'matched_skills' => json_encode($result['skills']),
            'skill_score' => $result['score'],
            'threshold_score' => 50,  // Admin configurable (Day 5)
            'is_valid' => $result['score'] >= 50,
        ]);

        return redirect()->route('resume.status', $resume);
    }

private function calculateSkillScore($text): array
{
    $skills = Skill::where('is_active', true)
        ->get(['normalized_name', 'weight']);
        
    $matchedSkills = [];
    $totalScore = 0;
    
    // ✅ Case insensitive + clean text
    $textClean = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', ' ', $text));
    
    foreach ($skills as $skill) {
        $skillLower = strtolower($skill->normalized_name);
        
        // ✅ Multiple matching strategies
        if (stripos($textClean, $skillLower) !== false ||
            preg_match('/\b' . preg_quote($skillLower, '/') . '\b/i', $textClean)) {
            
            $matchedSkills[] = [
                'name' => $skill->normalized_name,
                'weight' => $skill->weight
            ];
            $totalScore += $skill->weight;
        }
    }
    
    // ✅ Debug: Log unmatched text
    \Log::info('Resume text preview: ' . substr($textClean, 0, 200));
    \Log::info('Matched skills: ' . json_encode($matchedSkills));
    
    return [
        'skills' => $matchedSkills,
        'score' => $totalScore
    ];
}
public function index()
{
    // 🎯 CHECK if user has resume OR active assessment
    $hasResume = Resume::where('user_id', auth()->id())->exists();  // ✅ FIXED!
    $hasActiveAssessment = UserAssessment::where('user_id', auth()->id())
        ->where('is_completed', false)
        ->exists();
    
    // 🎯 If has assessment → Go to assessment
    if ($hasActiveAssessment) {
        return redirect('/assessment/start');
    }
    
    // 🎯 If has resume → Go to status
    if ($hasResume) {
        $resume = \App\Models\Resume::where('user_id', auth()->id())->latest()->first();
        return redirect()->route('resume.status', $resume);
    }
    
    // 🎯 No resume → Show upload
    return view('resume.upload');
}

public function status(Resume $resume)
{
    abort_if($resume->user_id !== auth()->id(), 403);
    
    // ✅ AUTO-FIX: Recalculate if score is 0 or NULL
    if (!$resume->skill_score || !$resume->matched_skills) {
        $result = $this->calculateSkillScore($resume->parsed_text ?? '');
        
        $resume->update([
            'matched_skills' => json_encode($result['skills']),
            'skill_score' => $result['score'],
            'threshold_score' => 50,
            'is_valid' => $result['score'] >= 50,
        ]);
        
        $resume->refresh();
        \Log::info('AUTO-FIXED resume #' . $resume->id . ' → Score: ' . $result['score']);
    }
    
    return view('resume.status', compact('resume'));
}

}

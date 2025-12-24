<?php
namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\UserAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function start(Request $request)
{
    $assessment = Assessment::where('is_active', true)->firstOrFail();
    
    // 🎯 GET EXISTING OR CREATE NEW (PORTAL MODE!)
    $userAssessment = UserAssessment::where('user_id', Auth::id())
        ->where('assessment_id', $assessment->id)
        ->where('is_completed', false)
        ->first();
    
    // 🎯 AUTO-CREATE if back button pressed (NO delete!)
    if (!$userAssessment) {
        $resumeSkills = Auth::user()->resume?->matched_skills ?? [['name' => 'python'], ['name' => 'sql'], ['name' => 'docker']];
        $userSkills = array_column($resumeSkills, 'name');
        
        $questions = Question::whereIn('skill', $userSkills)
            ->inRandomOrder()->limit(20)->get();
            
        if ($questions->isEmpty()) {
            $questions = Question::inRandomOrder()->limit(20)->get();
        }
        
        $userAssessment = UserAssessment::create([
            'user_id' => Auth::id(),
            'resume_id' => Auth::user()->resume?->id ?? 1,
            'assessment_id' => $assessment->id,
            'selected_questions' => $questions->pluck('id')->toArray(),
            'started_at' => now(),
        ]);
    }
    
    // 🚀 SERVER TIMER (Continues after logout!)
    $durationSeconds = $assessment->duration_minutes * 60;
    $timeLeft = max(0, $durationSeconds - now()->diffInSeconds($userAssessment->started_at));
    
    // Auto-submit if expired
    if ($timeLeft <= 0 && !$userAssessment->is_completed) {
        $score = $this->calculateScore($userAssessment);
        $userAssessment->update([
            'score' => $score,
            'is_completed' => true,
            'ended_at' => now(),
        ]);
        return redirect("/assessment/results/{$userAssessment->id}");
    }
    
    $questions = Question::with('options')
        ->whereIn('id', $userAssessment->selected_questions)
        ->get();
    
    return view('assessment.start', compact('questions', 'userAssessment', 'assessment', 'timeLeft'));
}


    public function save(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:user_assessments,id',
            'answers' => 'array'
        ]);

        $userAssessment = UserAssessment::where('id', $request->assessment_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $userAssessment->update([
            'user_answers' => $request->answers ?? []
        ]);
        
        return response()->json(['status' => 'saved']);
    }

    public function timeSync($userAssessmentId)
    {
        $userAssessment = UserAssessment::where('id', $userAssessmentId)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        $assessment = $userAssessment->assessment;
        $durationSeconds = $assessment->duration_minutes * 60;
        $timeLeft = max(0, $durationSeconds - now()->diffInSeconds($userAssessment->started_at));
        
        // AUTO-SUBMIT if expired
        if ($timeLeft <= 0 && !$userAssessment->is_completed) {
            $score = $this->calculateScore($userAssessment);
            $userAssessment->update([
                'score' => $score,
                'is_completed' => true,
                'ended_at' => now(),
            ]);
        }
        
        return response()->json(['timeLeft' => $timeLeft]);
    }

    public function results($userAssessmentId)
    {
        $userAssessment = UserAssessment::where('id', $userAssessmentId)
            ->where('user_id', auth()->id())
            ->with('assessment')
            ->firstOrFail();
        
        if (!$userAssessment->is_completed) {
            $score = $this->calculateScore($userAssessment);
            $userAssessment->update([
                'user_answers' => $userAssessment->user_answers ?? [],
                'score' => $score,
                'is_completed' => true,
                'ended_at' => now(),
            ]);
        }
        
        $assessment = $userAssessment->assessment;
        $totalQuestions = count($userAssessment->selected_questions);
        $percentage = $totalQuestions > 0 ? ($userAssessment->score / $totalQuestions) * 100 : 0;
        $isPassed = $percentage >= $assessment->pass_percentage;
        
        return view('assessment.results', compact('userAssessment', 'assessment', 'percentage', 'isPassed'));
    }

    private function calculateScore($userAssessment)
    {
        $score = 0;
        $answers = $userAssessment->user_answers ?? [];
        
        foreach ($userAssessment->selected_questions as $questionId) {
            $question = Question::with('options')->find($questionId);
            $userAnswer = $answers[$questionId] ?? null;
            
            if ($question && $userAnswer) {
                $correctOption = $question->options->where('is_correct', true)->first();
                if ($correctOption && $correctOption->order == $userAnswer) {
                    $score++;
                }
            }
        }
        
        return $score;
    }
}

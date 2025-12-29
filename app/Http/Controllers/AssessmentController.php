<?php
    namespace App\Http\Controllers;

    use App\Models\Assessment;
    use App\Models\Question;
    use App\Models\Resume;
    use App\Models\UserAssessment;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class AssessmentController extends Controller
    {
        public function instructions()
        {
            $assessment = Assessment::where('is_active', true)->firstOrFail();
            $userResume = Resume::where('user_id', Auth::id())->latest()->first();
            
            if (!$userResume || !$userResume->is_valid || $userResume->skill_score < $userResume->threshold_score) {
                return redirect()->route('resume.upload')
                    ->with('error', 'Resume does not meet assessment criteria.');
            }
            
            return view('assessment.instructions', compact('assessment'));
        }
        public function start(Request $request)
        {   
            

            $assessment = Assessment::where('is_active', true)->firstOrFail();
             
            // ✅ REQUIRE RESUME FIRST
            $userResume = Resume::where('user_id', Auth::id())->latest()->first();
            
            if (!$userResume || !$userResume->is_valid || $userResume->skill_score < $userResume->threshold_score) {
                return redirect()->route('resume.upload')
                    ->with('error', 'Resume does not meet assessment criteria.');
            }
            
            // ✅ CHECK FOR EXISTING INCOMPLETE ASSESSMENT
            $userAssessment = UserAssessment::where('user_id', Auth::id())
                ->where('assessment_id', $assessment->id)
                ->where('is_completed', false)
                ->orderBy('started_at', 'asc')
                ->first();
            
            // ✅ IF EXISTS, CHECK IF TIME EXPIRED
            if ($userAssessment) {
                $elapsed = now()->diffInSeconds($userAssessment->started_at);
                $timeLeft = (int) max(0, $userAssessment->total_duration_seconds - $elapsed);



                
                // If time expired, complete it and redirect to results
                if ($timeLeft <= 0) {
                    $score = $this->calculateScore($userAssessment);
                    $userAssessment->update([
                        'score' => $score,
                        'is_completed' => true,
                        'ended_at' => now(),
                    ]);
                    return redirect()->route('assessment.results', $userAssessment->id)
                        ->with('info', 'Your previous assessment has expired.');
                }
            }
            
            // ✅ CREATE NEW ASSESSMENT IF NONE EXISTS
            if (!$userAssessment) {
                // Safe JSON decode for skills
                $resumeSkillsRaw = $userResume->matched_skills;
                $resumeSkills = is_string($resumeSkillsRaw) ? json_decode($resumeSkillsRaw, true) : $resumeSkillsRaw;
                $resumeSkills = is_array($resumeSkills) ? $resumeSkills : [];
                
                if (empty($resumeSkills)) {
                    return redirect()->route('resume.status', $userResume)
                        ->with('error', 'No skills detected in your resume.');
                }
                
                // Extract skill names
                $userSkills = [];
                foreach($resumeSkills as $skill) {
                    if (is_array($skill) && isset($skill['name'])) {
                        $userSkills[] = $skill['name'];
                    } elseif (is_string($skill)) {
                        $userSkills[] = $skill;
                    }
                }
                
                // Get questions matching user skills
                $questions = Question::whereIn('skill', $userSkills)
                    ->inRandomOrder()
                    ->limit(20)
                    ->get();
                
                // Fallback to random questions if no skill match
                if ($questions->isEmpty()) {
                    $questions = Question::inRandomOrder()->limit(20)->get();
                }
                
                // Create new assessment
                $adminDuration = (int) (15 * 60);
                $userAssessment = UserAssessment::create([
                    'user_id' => Auth::id(),
                    'resume_id' => $userResume->id,
                    'assessment_id' => $assessment->id,
                    'total_duration_seconds' => $adminDuration,
                    'selected_questions' => $questions->pluck('id')->toArray(),
                    'started_at' => now(),
                    'is_completed' => false,
                ]);
            }
            
            // ✅ CALCULATE TIME LEFT
            $elapsed = now()->diffInSeconds($userAssessment->started_at);
            $timeLeft = max(0, $userAssessment->total_duration_seconds - $elapsed);
            
            // Double check time hasn't expired
            if ($timeLeft <= 0 && !$userAssessment->is_completed) {
                $score = $this->calculateScore($userAssessment);
                $userAssessment->update([
                    'score' => $score,
                    'is_completed' => true,
                    'ended_at' => now(),
                ]);
                return redirect()->route('assessment.results', $userAssessment->id);
            }
            
            // ✅ LOAD QUESTIONS WITH OPTIONS
            $questions = Question::with('options')
                ->whereIn('id', $userAssessment->selected_questions)
                ->get();
            
            return view('assessment.start', [
                'assessment' => $assessment,
                'questions' => $questions,
                'result' => $userAssessment,
                'timeLeft' => $timeLeft,
            ]);
        }

        public function save(Request $request)
        {
            $request->validate([
                'assessment_id' => 'required|exists:user_assessments,id',
                'answers' => 'array'
            ]);

            $userAssessment = UserAssessment::where('id', $request->assessment_id)
                ->where('user_id', Auth::id())
                ->where('is_completed', false)
                ->firstOrFail();
            
            $userAssessment->update([
                'user_answers' => $request->answers ?? []
            ]);
            
            return response()->json(['status' => 'saved']);
        }

public function logViolation(Request $request)
{
    \Log::info('Violation endpoint hit!', $request->all());
    
    $request->validate([
        'assessment_id' => 'required|exists:user_assessments,id',
        'violation_type' => 'required|string|max:100',
    ]);

    $userAssessment = UserAssessment::where('id', $request->assessment_id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    // Get current count BEFORE increment
    $currentCount = (int) ($userAssessment->violation_count ?? 0);
    
    // Append violation
    $violations = $userAssessment->violations ?? [];
    $violations[] = [
        'type' => $request->violation_type,
        'timestamp' => now()->toDateTimeString(),
    ];

    $userAssessment->update([
        'violations' => $violations,
        'violation_count' => $currentCount + 1  // ✅ FIX: Use variable
    ]);

    \Log::info('Violation LOGGED!', [
        'assessment_id' => $userAssessment->id,
        'new_count' => $currentCount + 1,
        'array_length' => count($violations)
    ]);

    return response()->json([
        'status' => 'logged',
        'total_violations' => $currentCount + 1  // ✅ Returns correct count
    ]);
}



        public function results($userAssessmentId)
        {
            $userAssessment = UserAssessment::where('id', $userAssessmentId)
                ->where('user_id', Auth::id())
                ->with('assessment')
                ->firstOrFail();
            
            // Complete if not already completed
            if (!$userAssessment->is_completed) {
                $score = $this->calculateScore($userAssessment);
                $userAssessment->update([
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

        public function calculateScore($userAssessment)
        {
            $score = 0;
            $answers = $userAssessment->user_answers ?? [];
            
            foreach ($userAssessment->selected_questions as $questionId) {
                $question = Question::with('options')->find($questionId);
                
                if (!$question) continue;
                
                $userAnswer = $answers[$questionId] ?? null;
                
                if ($userAnswer) {
                    $correctOption = $question->options->where('is_correct', true)->first();
                    if ($correctOption && $correctOption->order == $userAnswer) {
                        $score++;
                    }
                }
            }
            
            return $score;
        }
    }
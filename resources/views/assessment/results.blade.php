<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assessment->name }} - Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen p-4 md:p-8">
    <div class="container mx-auto max-w-6xl">
        <!-- HERO SCORE -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 mb-8 text-center">
            @if($isPassed)
            <div class="w-32 h-32 bg-gradient-to-r from-green-400 to-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-5xl md:text-6xl font-black bg-gradient-to-r from-green-600 to-green-700 bg-clip-text text-transparent mb-4">PASSED!</h1>
            @else
            <div class="w-32 h-32 bg-gradient-to-r from-red-400 to-red-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h1 class="text-5xl md:text-6xl font-black bg-gradient-to-r from-red-600 to-red-700 bg-clip-text text-transparent mb-4">TRY AGAIN</h1>
            @endif

            <div class="bg-gradient-to-r {{ $isPassed ? 'from-emerald-400 to-green-500' : 'from-rose-400 to-red-500' }} rounded-3xl p-12 mb-8 shadow-2xl">
                <h2 class="text-7xl md:text-8xl font-black text-white mb-4 drop-shadow-2xl">
                    {{ $userAssessment->score }} / {{ count($userAssessment->selected_questions) }}
                </h2>
                <div class="text-4xl md:text-5xl font-black text-white/90 mb-2">{{ number_format($percentage, 1) }}%</div>
                <div class="text-xl text-white/80">Passing: {{ $assessment->pass_percentage }}%</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 mb-12">
            <!-- STATS -->
            <div class="bg-white rounded-3xl shadow-2xl p-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-8 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Performance Stats
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div>
                        <div class="text-4xl font-black text-emerald-600">{{ $userAssessment->score }}</div>
                        <div class="text-sm text-gray-500 mt-1">Correct</div>
                    </div>
                    <div>
                        <div class="text-4xl font-black text-red-600">{{ count($userAssessment->selected_questions) - $userAssessment->score }}</div>
                        <div class="text-sm text-gray-500 mt-1">Wrong</div>
                    </div>
                    <div>
                        <div class="text-4xl font-black text-blue-600">{{ number_format($percentage, 0) }}%</div>
                        <div class="text-sm text-gray-500 mt-1">Score</div>
                    </div>
                    <div>
                        <div class="text-4xl font-black text-gray-600">{{ $assessment->pass_percentage }}%</div>
                        <div class="text-sm text-gray-500 mt-1">To Pass</div>
                    </div>
                </div>
            </div>

            <!-- ASSESSMENT INFO -->
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-3xl shadow-2xl p-8 border-2 border-indigo-100">
                <h3 class="text-2xl font-bold text-indigo-900 mb-6">📋 Assessment Details</h3>
                <div class="space-y-4 text-lg">
                    <div class="flex justify-between p-4 bg-white/50 rounded-2xl backdrop-blur-sm">
                        <span class="font-semibold text-gray-700">Name</span>
                        <span class="font-bold text-gray-900">{{ $assessment->name }}</span>
                    </div>
                    <div class="flex justify-between p-4 bg-white/50 rounded-2xl backdrop-blur-sm">
                        <span class="font-semibold text-gray-700">Duration</span>
                        <span class="font-bold text-gray-900">{{ $assessment->duration_minutes }} min</span>
                    </div>
                    <div class="flex justify-between p-4 bg-white/50 rounded-2xl backdrop-blur-sm">
                        <span class="font-semibold text-gray-700">Completed</span>
                        <span class="font-bold text-gray-900">{{ $userAssessment->ended_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- EXPANDABLE QUESTION REVIEW (MOST VALUABLE) -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-12">
            <h3 class="text-3xl font-bold text-gray-800 mb-8 flex items-center justify-center">
                <svg class="w-10 h-10 mr-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Detailed Question Review
            </h3>
            
<!-- Replace the question review section (lines 96-150) with this: -->
🔧 INSTANT FIX (Safe Null Check)
Error: $userAssessment->questionData is null!

✅ WORKING CODE (Copy-Paste This):
xml
<!-- Replace lines 96-150 COMPLETELY with this SAFE version: -->
<div class="space-y-4 max-h-96 overflow-y-auto">
    @foreach($userAssessment->selected_questions as $index => $questionId)
        @php
            // SAFE data access - works with ANY structure
            $questionData = $userAssessment->questionData 
                ? $userAssessment->questionData->where('question_id', $questionId)->first()
                : null;
            $question = $questionData?->question ?? null;
            $isCorrect = $questionData?->is_correct ?? false;
            $userAnswer = $questionData?->user_answer ?? 0;
            
            // Fallback if no question data
            $userAnswerText = $question?->options?->where('order', $userAnswer)->first()->option_text ?? 'N/A';
            $correctAnswerText = $question?->options?->where('order', $question?->correct_option_order ?? 0)->first()->option_text ?? 'N/A';
            $questionText = $question?->question ?? 'Question #' . ($index + 1);
        @endphp
        
        <div class="question-review border {{ $isCorrect ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }} rounded-2xl p-6 transition-all hover:shadow-md">
            <div class="flex items-center justify-between cursor-pointer group" onclick="toggleReview({{ $index }})">
                <div class="flex items-center flex-1">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-xl mr-4
                        {{ $isCorrect ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-lg text-gray-900 truncate">{{ $questionText }}</div>
                        <div class="text-sm {{ $isCorrect ? 'text-green-700' : 'text-red-700' }}">
                            {{ $isCorrect ? '✅ Correct' : '❌ Incorrect' }}
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4 ml-4">
                    <div class="text-center">
                        <div class="text-xs font-medium text-gray-500">Your Answer</div>
                        <span class="text-xl font-black px-3 py-1 rounded-lg bg-gray-200">
                            {{ chr(64 + $userAnswer) }}
                        </span>
                    </div>
                    <svg id="arrow-{{ $index }}" class="w-6 h-6 transition-transform {{ $isCorrect ? 'text-green-500' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
            
            <!-- EXPANDABLE CONTENT -->
            <div id="content-{{ $index }}" class="mt-6 p-6 bg-white rounded-xl border-l-4 {{ $isCorrect ? 'border-green-400' : 'border-red-400' }} hidden shadow-sm">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="font-bold text-lg text-gray-800 mb-4">Your Answer:</h5>
                        <div class="p-4 bg-gradient-to-r from-yellow-100 to-yellow-200 rounded-xl border-2 border-yellow-300">
                            <div class="flex items-center mb-2">
                                <span class="text-2xl font-black text-yellow-800 mr-3">{{ chr(64 + $userAnswer) }}</span>
                                <div class="text-sm font-medium text-yellow-800 bg-white px-3 py-1 rounded-full">Your Selection</div>
                            </div>
                            <div class="text-gray-800">{{ $userAnswerText }}</div>
                        </div>
                    </div>
                    @if(!$isCorrect && $question)
                    <div>
                        <h5 class="font-bold text-lg text-emerald-700 mb-4">✓ Correct Answer:</h5>
                        <div class="p-4 bg-gradient-to-r from-emerald-100 to-emerald-200 rounded-xl border-2 border-emerald-400">
                            <div class="flex items-center mb-2">
                                <span class="text-2xl font-black text-emerald-700 mr-3">{{ chr(64 + ($question->correct_option_order ?? 0)) }}</span>
                                <div class="text-sm font-medium text-emerald-700 bg-white px-3 py-1 rounded-full">Correct</div>
                            </div>
                            <div class="text-gray-800">{{ $correctAnswerText }}</div>
                        </div>
                    </div>
                    @endif
                </div>
                
                @if(!$isCorrect)
                <div class="mt-6 pt-6 border-t border-gray-200 bg-red-50 p-4 rounded-xl">
                    <div class="font-bold text-red-700 text-lg mb-3">💡 Quick Tip</div>
                    <div class="text-gray-700">
                        Focus on understanding why the correct answer is right. Practice similar questions!
                    </div>
                </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

        <!-- PDF DOWNLOAD + ACTION BUTTONS -->
        <div class="text-center mb-12 space-y-6">
            <!-- PDF BUTTON (Your NEW feature!) -->
            <a href="{{ route('assessment.download-report', $userAssessment->id) }}" 
               class="inline-flex items-center bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white font-bold py-4 px-12 rounded-3xl text-xl shadow-2xl hover:shadow-3xl transform hover:-translate-y-2 transition-all duration-300">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                📄 Download Detailed Report
            </a>

            <!-- ACTION BUTTONS -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-8 border-t border-gray-200">
                <a href="/assessment/start" 
                   class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-bold py-4 px-12 rounded-2xl text-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 flex-1 text-center">
                    🔄 Take Again
                </a>
                <a href="/resume-upload" 
                   class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-4 px-12 rounded-2xl text-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 flex-1 text-center">
                    ← Dashboard
                </a>
            </div>
        </div>
    </div>

    <script>
        function toggleReview(index) {
            const content = document.getElementById(`content-${index}`);
            const arrow = document.getElementById(`arrow-${index}`);
            content.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }
    </script>
</body>
</html>

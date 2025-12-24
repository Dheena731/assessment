<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assessment - {{ floor($timeLeft / 60) }}:{{ str_pad($timeLeft % 60, 2, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .timer-container { text-align: center; margin: 20px 0; }
        .progress-bar { width: 100%; height: 10px; background: #e0e0e0; border-radius: 5px; overflow: hidden; margin-bottom: 10px; }
        #progress { height: 100%; background: linear-gradient(90deg, #ff6b6b, #4ecdc4); transition: width 0.3s ease; }
        .timer { font-size: 2.5em; font-weight: bold; color: #333; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen p-8">
    <div class="container mx-auto max-w-4xl">
        <div class="bg-white rounded-xl shadow-2xl p-8">
            <!-- HEADER + SERVER TIMER -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">
                    {{ $assessment->name }}
                </h1>
                <div class="text-right">
                    <div class="text-3xl font-bold text-red-600" id="timer">
                        {{ floor($timeLeft / 60) }}:{{ str_pad($timeLeft % 60, 2, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $assessment->duration_minutes }} minutes total
                    </div>
                </div>
            </div>

            <!-- SERVER-SYNC PROGRESS BAR -->
            <div class="mb-6">
                <div class="progress-bar">
                    <div id="progress" style="width: {{ (($assessment->duration_minutes * 60 - $timeLeft) / ($assessment->duration_minutes * 60)) * 100 }}%"></div>
                </div>
                <div class="text-sm text-gray-500 mt-1">
                    Question <span id="current-q">1</span> of {{ $questions->count() }}
                </div>
            </div>

            <!-- QUESTIONS -->
            <form id="assessment-form">
                @foreach($questions as $index => $question)
                <div class="question border-l-4 border-blue-500 pl-6 py-6 mb-4 bg-gray-50 rounded-lg" 
                     data-question-id="{{ $question->id }}"
                     data-hint="{{ $question->hint ?? 'Think carefully!' }}">
                    <div class="font-semibold text-lg mb-3">
                        Q{{ $index + 1 }}. {{ $question->question }}
                        @if($question->hint)
                        <button type="button" class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full hover:bg-yellow-200 hint-btn">
                            💡 Hint
                        </button>
                        @endif
                    </div>

                    @foreach($question->options->sortBy('order') as $option)
                    <label class="flex items-center p-3 mb-2 hover:bg-white rounded-lg cursor-pointer transition-all option-label">
                        <input type="radio" 
                               name="question_{{ $question->id }}" 
                               value="{{ $option->order }}"
                               class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 mr-3"
                               {{ old("question_{$question->id}") == $option->order ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">
                            {{ chr(64 + $option->order) }}. {{ $option->option_text }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @endforeach

                <!-- SUBMIT BUTTON -->
                <div class="flex justify-between pt-6 border-t">
                    <button type="button" id="submit-btn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl text-lg transition-all">
                        ⏰ Submit Assessment
                    </button>
                    <div class="text-sm text-gray-500 self-center">
                        Need {{ $assessment->pass_percentage }}% to pass
                    </div>
                </div>
            </form>

            <!-- HINT MODAL -->
            <div id="hint-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
                <div class="bg-white p-6 rounded-xl max-w-md w-full max-h-96 overflow-y-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">💡 Hint</h3>
                        <button id="close-hint" class="text-2xl font-bold text-gray-500 hover:text-gray-700">&times;</button>
                    </div>
                    <div id="hint-content" class="text-gray-800 text-sm leading-relaxed"></div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="user-assessment-id" value="{{ $userAssessment->id }}">

    <!-- 🎯 PRODUCTION SERVER-SYNC TIMER -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let timeLeft = {{ $timeLeft }};  // 🎯 SERVER TIME!
        let timerInterval;
        const assessmentId = {{ $userAssessment->id }};
        const totalDuration = {{ $assessment->duration_minutes * 60 }};
        
        function updateTimer() {
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                submitAssessment();
                return;
            }
            
            // 🔄 SERVER SYNC every 30s
            if (timeLeft % 30 === 0 && timeLeft > 0) {
                fetch(`/assessment/time-sync/${assessmentId}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.timeLeft !== undefined) {
                            timeLeft = data.timeLeft;
                        }
                    }).catch(() => {}); // Silent fail
            }
            
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            document.getElementById('timer').textContent = 
                `${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
            
            const progress = ((totalDuration - timeLeft) / totalDuration) * 100;
            document.getElementById('progress').style.width = `${progress}%`;
            
            timeLeft--;
        }
        
        // AUTO-SAVE ANSWERS
        function saveAnswers() {
            const answers = {};
            document.querySelectorAll('input[type="radio"]:checked').forEach(input => {
                const qId = input.name.replace('question_', '');
                answers[qId] = input.value;
            });
            
            fetch('/assessment/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    assessment_id: assessmentId,
                    answers: answers
                })
            });
        }
        
        // SUBMIT
        function submitAssessment() {
            saveAnswers();
            document.body.innerHTML = `
                <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-500 to-purple-600">
                    <div class="bg-white p-12 rounded-3xl shadow-2xl text-center max-w-md mx-auto">
                        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Submitting Assessment...</h2>
                        <p class="text-gray-600">Redirecting to results</p>
                    </div>
                </div>
            `;
            setTimeout(() => {
                window.location.href = '/assessment/results/' + assessmentId;
            }, 2000);
        }
        
        // INIT
        timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
        
        // EVENTS
        document.getElementById('submit-btn').onclick = submitAssessment;
        setInterval(saveAnswers, 5000); // Auto-save every 5s
        
        // HINTS
        document.querySelectorAll('.hint-btn').forEach(btn => {
            btn.onclick = function() {
                const questionDiv = this.closest('.question');
                const hint = questionDiv.dataset.hint;
                document.getElementById('hint-content').textContent = hint;
                document.getElementById('hint-modal').classList.remove('hidden');
            };
        });
        
        document.getElementById('close-hint').onclick = function() {
            document.getElementById('hint-modal').classList.add('hidden');
        };
        
        document.getElementById('hint-modal').onclick = function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        };
    });
    </script>
</body>
</html>

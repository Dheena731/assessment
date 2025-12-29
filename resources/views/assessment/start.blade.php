    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $assessment->name }} - Assessment</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-50">
        
<!-- TOP HEADER BAR WITH LOGOUT -->
<div class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            <!-- LEFT: Title -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $assessment->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">Answer all questions before submitting</p>
            </div>
            
            <!-- RIGHT: Timer + Logout -->
            <div class="flex items-center space-x-4">
                <!-- TIMER -->
                <div class="text-right">
                    <div class="text-sm text-gray-500 mb-1">Time Remaining</div>
                    <div class="text-3xl font-bold text-gray-900" id="timer">
                        {{ floor($timeLeft / 60) }}:{{ str_pad($timeLeft % 60, 2, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
                
                <!-- LOGOUT BUTTON -->
                <div class="flex flex-col items-end">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-lg text-sm transition-all duration-200 shadow-sm hover:shadow-md flex items-center space-x-1"
                                onclick="return confirm('⚠️ Logout will END your assessment!\nContinue answering or submit first.\n\nReally logout?')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-gray-600 mb-2">
                        <span>Progress: <span id="answered-count" class="font-bold text-blue-600">0</span> / {{ $questions->count() }}</span>
                        <span id="progress-text">0%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div id="progress" class="h-full bg-gradient-to-r from-blue-500 to-purple-600 transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- VIOLATIONS WARNING -->
        <div id="violation-warning" class="hidden">
            <div class="container mx-auto px-6 py-3">
                <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
                    <div class="flex items-center">
                        <span class="text-red-600 font-bold text-xl mr-3">⚠️</span>
                        <span class="text-red-700 font-semibold">
                            Warning: <span id="violation-count">0</span>/3 violations detected. 
                            <span class="text-red-900">Assessment will auto-submit at 3 violations!</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="container mx-auto px-6 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                <!-- LEFT CONTENT - Questions -->
                <div class="lg:col-span-3">
                    <form id="assessment-form">
                        @foreach($questions as $index => $question)
                            <div class="question bg-white rounded-xl shadow-sm border-2 border-gray-200 p-8 mb-6 {{ $index > 0 ? 'hidden' : '' }}" 
                                data-q-num="{{ $index + 1 }}"
                                data-question-id="{{ $question->id }}">
                                
                                <!-- Question Header -->
                                <div class="flex items-start justify-between mb-6">
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-blue-600 mb-2">Question {{ $index + 1 }} of {{ $questions->count() }}</div>
                                        <h2 class="text-xl font-bold text-gray-900 leading-relaxed">
                                            {{ $question->question }}
                                        </h2>
                                    </div>
                                    <div class="ml-4">
                                        <span class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 text-blue-700 rounded-full font-bold text-lg">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Options -->
                                <div class="space-y-3">
                                    @foreach($question->options->sortBy('order') as $option)
                                        <label class="option-label flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 hover:border-blue-400 hover:bg-blue-50">
                                            <input type="radio" 
                                                name="question_{{ $question->id }}" 
                                                value="{{ $option->order }}"
                                                class="mt-1 w-5 h-5 text-blue-600 focus:ring-2 focus:ring-blue-500"
                                                data-question-num="{{ $index + 1 }}">
                                            <span class="ml-3 flex-1">
                                                <span class="inline-block w-8 font-bold text-gray-700">{{ chr(64 + $option->order) }}.</span>
                                                <span class="text-gray-800">{{ $option->option_text }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                                    <button type="button" 
                                            class="prev-btn px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all duration-200 {{ $index === 0 ? 'invisible' : '' }}">
                                        ← Previous
                                    </button>
                                    
                                    <div class="text-sm text-gray-500">
                                        Question {{ $index + 1 }} of {{ $questions->count() }}
                                    </div>

                                    @if($index < $questions->count() - 1)
                                        <button type="button" 
                                                class="next-btn px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200">
                                            Next →
                                        </button>
                                    @else
                                        <button type="button" 
                                                id="submit-btn" 
                                                class="px-8 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-all duration-200 shadow-lg">
                                            Submit Assessment
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>

                <!-- RIGHT SIDEBAR - Question Navigator -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sticky top-24">
                        <h3 class="font-bold text-gray-900 mb-4 text-lg">Question Navigator</h3>
                        
                        <!-- Legend -->
                        <div class="mb-4 space-y-2 text-xs bg-gray-50 p-3 rounded-lg">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-white border-2 border-gray-300 rounded"></div>
                                <span class="text-gray-700">Not Answered</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-green-500 border-2 border-green-500 rounded"></div>
                                <span class="text-gray-700">Answered</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-blue-500 border-2 border-blue-500 rounded shadow-lg shadow-blue-200"></div>
                                <span class="text-gray-700">Current</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-purple-500 border-2 border-purple-500 rounded shadow-lg shadow-purple-200"></div>
                                <span class="text-gray-700">Current + Answered</span>
                            </div>
                        </div>
                        
                        <!-- Question Buttons Grid -->
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($questions as $index => $question)
                                <button type="button" 
                                        class="q-nav h-12 flex items-center justify-center font-semibold rounded-lg border-2 transition-all duration-200 {{ $index === 0 ? 'bg-blue-500 text-white border-blue-500 shadow-lg shadow-blue-200' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}" 
                                        data-q="{{ $index + 1 }}"
                                        data-question-id="{{ $question->id }}">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Pass Info -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="text-sm text-gray-600">
                                <div class="flex justify-between mb-1">
                                    <span>Passing Score:</span>
                                    <span class="font-bold text-gray-900">{{ $assessment->pass_percentage }}%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total Questions:</span>
                                    <span class="font-bold text-gray-900">{{ $questions->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONFIRMATION MODAL -->
        <div id="confirm-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">⚠️</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Confirm Submission</h3>
                    <p class="text-gray-600">Are you sure you want to submit your assessment?</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Questions Answered:</span>
                        <span class="font-bold text-gray-900"><span id="confirm-answered">0</span> / {{ $questions->count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Unanswered:</span>
                        <span class="font-bold text-red-600" id="confirm-unanswered">{{ $questions->count() }}</span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button id="cancel-submit" 
                            class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-all duration-200">
                        Cancel
                    </button>
                    <button id="confirm-submit" 
                            class="flex-1 px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-all duration-200">
                        Yes, Submit
                    </button>
                </div>
            </div>
        </div>

        <script>
         
document.addEventListener('DOMContentLoaded', function() {
    // ============ STATE ============
    let timeLeft = parseInt({{ $timeLeft }});
    let timerInterval;
    let violations = 0;
    let currentQuestion = 1;
    let isSubmitting = false;
    const totalQuestions = {{ $questions->count() }};
    const MAX_VIOLATIONS = 3;
    const assessmentId = {{ $result->id }};

    // Question ID map
    const questionIds = {
        @foreach($questions as $index => $question)
            {{ $index + 1 }}: {{ $question->id }},
        @endforeach
    };
    const savedAnswersKey = 'assessment_answers_' + assessmentId;

    // ============ CORE FUNCTIONS ============
    function updateAnswerCount() {
        let answered = 0;
        for (let qNum = 1; qNum <= totalQuestions; qNum++) {
            const questionId = questionIds[qNum];
            const input = document.querySelector(`input[name="question_${questionId}"]:checked`);
            const btn = document.querySelector(`.q-nav[data-q="${qNum}"]`);
            
            if (input) {
                answered++;
                if (btn && !btn.classList.contains('answered')) {
                    btn.classList.add('answered');
                    // Update current question styling
                    if (btn.classList.contains('bg-blue-500')) {
                        btn.classList.remove('bg-blue-500', 'border-blue-500', 'shadow-blue-200');
                        btn.classList.add('bg-purple-500', 'border-purple-500', 'shadow-purple-200');
                    }
                }
            } else if (btn) {
                btn.classList.remove('answered');
            }
        }
        document.getElementById('answered-count').textContent = answered;
        const progress = Math.round((answered / totalQuestions) * 100);
        document.getElementById('progress').style.width = `${progress}%`;
        document.getElementById('progress-text').textContent = `${progress}%`;
        return answered;
    }


    function saveAnswers() {
        const answers = {};
        for (let qNum = 1; qNum <= totalQuestions; qNum++) {
            const questionId = questionIds[qNum];
            const input = document.querySelector(`input[name="question_${questionId}"]:checked`);
            if (input) answers[questionId] = input.value;
        }
        
        localStorage.setItem(savedAnswersKey, JSON.stringify(answers));
        console.log('💾 Saved', Object.keys(answers).length, 'answers');
        
        if (Object.keys(answers).length > 0) {
            fetch('/assessment/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ assessment_id: assessmentId, answers })
            }).catch(err => console.error('Server save failed:', err));
        }
    }

    function restoreAnswers() {
        console.log('🔍 localStorage:', localStorage.getItem(savedAnswersKey));
        const savedAnswers = JSON.parse(localStorage.getItem(savedAnswersKey) || '{}');
        console.log('🔍 Found:', Object.keys(savedAnswers).length, 'answers');
        
        Object.keys(savedAnswers).forEach(questionIdStr => {
            const questionId = parseInt(questionIdStr);
            const value = savedAnswers[questionIdStr];
            const input = document.querySelector(`input[name="question_${questionId}"][value="${value}"]`);
            
            if (input) {
                input.checked = true;
                const label = input.closest('.option-label');
                label?.classList.remove('border-gray-200');
                label?.classList.add('border-blue-500', 'bg-blue-50');
                console.log(`✅ Restored Q${questionId}: ${value}`);
            }
        });
        
        setTimeout(updateAnswerCount, 100);
        console.log('🎉 Restore complete');
    }

    // ============ 1. RESTORE ANSWERS ============
    restoreAnswers();

    // ============ 2. TIMER ============
    const savedTimeLeft = localStorage.getItem('assessment_timeLeft_' + assessmentId);
    if (savedTimeLeft !== null) {
        timeLeft = parseInt(savedTimeLeft);
        console.log('📱 Timer resumed:', timeLeft);
    }

    function updateTimer() {
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            localStorage.removeItem('assessment_timeLeft_' + assessmentId);
            autoSubmit('Time expired');
            return;
        }
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        document.getElementById('timer').textContent = 
            `${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
        localStorage.setItem('assessment_timeLeft_' + assessmentId, timeLeft);
        if (timeLeft <= 300) {
            document.getElementById('timer').classList.add('text-red-600');
            document.getElementById('timer').classList.remove('text-gray-900');
        }
        timeLeft--;
    }
    timerInterval = setInterval(updateTimer, 1000);
    updateTimer();

    // ============ 3. NAVIGATION ============
    function showQuestion(num) {
        document.querySelectorAll('.question').forEach(q => q.classList.add('hidden'));
        document.querySelector(`[data-q-num="${num}"]`).classList.remove('hidden');
        
        document.querySelectorAll('.q-nav').forEach(btn => {
            const btnNum = parseInt(btn.dataset.q);
            const isAnswered = btn.classList.contains('answered');
            btn.classList.remove('bg-blue-500', 'bg-purple-500', 'bg-green-500', 'bg-white', 
                              'text-white', 'text-gray-600', 'border-blue-500', 'border-purple-500', 
                              'border-green-500', 'border-gray-300', 'shadow-lg', 'shadow-blue-200', 'shadow-purple-200');
            if (btnNum === num) {
                // CURRENT QUESTION
                if (isAnswered) {
                    // Current + Answered = PURPLE
                    btn.classList.add('bg-purple-500', 'text-white', 'border-purple-500', 'shadow-lg', 'shadow-purple-200');
                } else {
                    // Current only = BLUE
                    btn.classList.add('bg-blue-500', 'text-white', 'border-blue-500', 'shadow-lg', 'shadow-blue-200');
                }
            } else if (isAnswered) {
                // Other Answered = GREEN
                btn.classList.add('bg-green-500', 'text-white', 'border-green-500');
            } else {
                // Unanswered = GRAY
                btn.classList.add('bg-white', 'text-gray-600', 'border-gray-300');
            }
    });
    
    currentQuestion = num;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

    // Navigation listeners
    document.querySelectorAll('.q-nav').forEach(btn => {
        btn.addEventListener('click', () => showQuestion(parseInt(btn.dataset.q)));
    });
    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', () => currentQuestion > 1 && showQuestion(currentQuestion - 1));
    });
    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', () => currentQuestion < totalQuestions && showQuestion(currentQuestion + 1));
    });

    // ============ 4. RADIO BUTTONS ============
    document.querySelectorAll('input[type="radio"]').forEach(input => {
        input.addEventListener('change', function() {
            this.closest('.question').querySelectorAll('.option-label').forEach(label => {
                label.classList.remove('border-blue-500', 'bg-blue-50');
                label.classList.add('border-gray-200');
            });
            const label = this.closest('.option-label');
            label.classList.remove('border-gray-200');
            label.classList.add('border-blue-500', 'bg-blue-50');
            updateAnswerCount();
            saveAnswers(); // IMMEDIATE SAVE
        });
    });

    // ============ 5. ANTI-CHEAT ============
    function registerViolation(reason) {
        violations++;
        fetch('/assessment/log-violation', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ assessment_id: assessmentId, violation_type: reason })
        }).catch(console.error);
        
        const warningEl = document.getElementById('violation-warning');
        const countEl = document.getElementById('violation-count');
        warningEl.classList.remove('hidden');
        countEl.textContent = violations;
        console.warn('🚨 VIOLATION:', reason);
        if (violations >= MAX_VIOLATIONS) setTimeout(() => autoSubmit(`3 violations: ${reason}`), 1000);
    }
            //     document.addEventListener('keydown', e => {
            //     if (e.key === 'F12' || 
            //         (e.ctrlKey && e.shiftKey && ['I', 'C', 'J'].includes(e.key)) ||
            //         (e.ctrlKey && ['s', 'p', 'a', 'u'].includes(e.key.toLowerCase())) ||
            //         e.key === 'F5') {
            //         e.preventDefault();
            //         registerViolation('KEYBOARD_SHORTCUT');  // 🚨 CRITICAL
            //     }
            // });

    ['copy', 'cut', 'paste'].forEach(evt => {
        document.addEventListener(evt, e => { e.preventDefault(); registerViolation(`${evt.toUpperCase()}_ATTEMPT`); });
    });
    document.addEventListener('visibilitychange', () => document.hidden && registerViolation('TAB_SWITCHED'));
    document.addEventListener('selectstart', e => e.preventDefault());
    document.addEventListener('mouseup', () => window.getSelection()?.removeAllRanges());
    document.addEventListener('contextmenu', e => e.preventDefault());

    // ============ 6. SUBMIT ============
    function showConfirmModal() {
        const answered = updateAnswerCount();
        document.getElementById('confirm-answered').textContent = answered;
        document.getElementById('confirm-unanswered').textContent = totalQuestions - answered;
        document.getElementById('confirm-modal').classList.remove('hidden');
    }

    function submitAssessment() {
        if (isSubmitting) return;
        isSubmitting = true;
        localStorage.removeItem('assessment_timeLeft_' + assessmentId);
        localStorage.removeItem(savedAnswersKey);
        clearInterval(timerInterval);
        saveAnswers();
        
        document.body.innerHTML = `
            <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-500 to-purple-600">
                <div class="bg-white p-12 rounded-3xl shadow-2xl text-center max-w-md mx-auto">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Assessment Submitted!</h2>
                    <p class="text-gray-600">Redirecting to results...</p>
                </div>
            </div>
        `;
        setTimeout(() => window.location.href = '/assessment/results/' + assessmentId, 2000);
    }

    function autoSubmit(reason) {
        alert(`Assessment auto-submitted: ${reason}`);
        submitAssessment();
    }

    // Event listeners
    document.getElementById('submit-btn')?.addEventListener('click', showConfirmModal);
    document.getElementById('confirm-submit')?.addEventListener('click', () => { 
        document.getElementById('confirm-modal').classList.add('hidden'); 
        submitAssessment(); 
    });
    document.getElementById('cancel-submit')?.addEventListener('click', () => {
        document.getElementById('confirm-modal').classList.add('hidden');
    });

    // ============ 7. AUTO-SAVE + INIT ============
    setInterval(saveAnswers, 10000); // EVERY 10s
    window.addEventListener('beforeunload', e => !isSubmitting && (e.preventDefault(), e.returnValue = ''));
    
    console.log('🚀 Assessment ready!');
});
</script>
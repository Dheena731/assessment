<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Instructions - {{ $assessment->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="container mx-auto px-6 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $assessment->name }}</h1>
                    <p class="text-gray-600 mt-1">Please read the instructions carefully before starting</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Time to read</div>
                    <div class="text-3xl font-bold text-gray-900" id="timer">2:00</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-8 max-w-4xl">
        
        <!-- Assessment Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Assessment Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="mr-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Duration</div>
                        <div class="text-lg font-bold text-gray-900">{{ $assessment->duration_minutes }} minutes</div>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="mr-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Questions</div>
                        <div class="text-lg font-bold text-gray-900">20 Questions</div>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="mr-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Passing Score</div>
                        <div class="text-lg font-bold text-gray-900">{{ $assessment->pass_percentage }}%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Instructions</h2>
            <div class="space-y-4 text-gray-700">
                <div class="flex items-start">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm mr-3 mt-0.5">1</span>
                    <p>The assessment consists of 20 multiple-choice questions based on your resume skills.</p>
                </div>
                <div class="flex items-start">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm mr-3 mt-0.5">2</span>
                    <p>You have {{ $assessment->duration_minutes }} minutes to complete all questions. The timer will start as soon as you begin.</p>
                </div>
                <div class="flex items-start">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm mr-3 mt-0.5">3</span>
                    <p>Each question has multiple options. Select the most appropriate answer.</p>
                </div>
                <div class="flex items-start">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm mr-3 mt-0.5">4</span>
                    <p>You can navigate between questions using the Previous and Next buttons, or by clicking question numbers in the navigator.</p>
                </div>
                <div class="flex items-start">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm mr-3 mt-0.5">5</span>
                    <p>Your answers are automatically saved every 10 seconds.</p>
                </div>
                <div class="flex items-start">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm mr-3 mt-0.5">6</span>
                    <p>Once you submit, you cannot change your answers. Make sure to review before submitting.</p>
                </div>
            </div>
        </div>

        <!-- Important Rules -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <!-- Allowed -->
            <div class="bg-green-50 rounded-lg border border-green-200 p-6">
                <h3 class="text-lg font-bold text-green-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    You Should
                </h3>
                <ul class="space-y-2 text-green-900">
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Read each question carefully</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Manage your time effectively</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Answer all questions if possible</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Review your answers before submitting</span>
                    </li>
                </ul>
            </div>

            <!-- Not Allowed -->
            <div class="bg-red-50 rounded-lg border border-red-200 p-6">
                <h3 class="text-lg font-bold text-red-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    Not Allowed
                </h3>
                <ul class="space-y-2 text-red-900">
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Using external resources (Google, ChatGPT, etc.)</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Switching tabs or windows</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Taking help from others</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Refreshing or closing the browser</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Warning Notice -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700 font-semibold">
                        Important: The system monitors for violations. Three violations will result in automatic submission of your assessment.
                    </p>
                </div>
            </div>
        </div>

        <!-- Start Button -->
        <div class="text-center">
            <button id="startBtn" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-12 rounded-lg text-lg shadow-lg hover:shadow-xl transition-all duration-200">
                Start Assessment Now
            </button>
            <p class="text-gray-600 mt-4" id="startHint">Click when you're ready to begin</p>
        </div>

    </div>

    <script>
        let readTime = 120; // 2 minutes
        const timerEl = document.getElementById('timer');
        const startBtn = document.getElementById('startBtn');
        const startHint = document.getElementById('startHint');

        function updateTimer() {
            const minutes = Math.floor(readTime / 60);
            const seconds = readTime % 60;
            timerEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

            if (readTime <= 0) {
                timerEl.textContent = '0:00';
                return;
            }

            if (readTime <= 30) {
                timerEl.classList.add('text-orange-600');
            }

            readTime--;
        }

        // Update timer every second
        setInterval(updateTimer, 1000);
        updateTimer();

        // Start button click
        startBtn.addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3 inline-block" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Starting...';
            
            setTimeout(() => {
                window.location.href = '/assessment/start';
            }, 1000);
        });
    </script>
</body>
</html>
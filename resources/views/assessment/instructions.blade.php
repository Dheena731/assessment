<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assessment Instructions - {{ $assessment->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
            50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.8); }
        }
        .pulse-glow { animation: pulse-glow 2s infinite; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-5xl">
        <!-- HEADER -->
        <div class="text-center mb-16">
            <div class="w-28 h-28 bg-gradient-to-r from-blue-500 to-purple-600 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-2xl border-4 border-white/50">
                <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <h1 class="text-5xl md:text-6xl font-black bg-gradient-to-r from-gray-900 via-gray-700 to-gray-500 bg-clip-text text-transparent mb-6 leading-tight">
                Assessment Instructions
            </h1>
            <p class="text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Read carefully. 2 minutes reading time. Your assessment starts after.
            </p>
        </div>

        <!-- INSTRUCTION TIMER -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl p-10 mb-16 border border-white/60 max-w-2xl mx-auto">
            <div class="text-center">
                <div class="text-6xl font-black text-blue-600 mb-4 font-mono tracking-wider" id="instructionTimer">2:00</div>
                <div class="text-2xl font-semibold text-gray-700">Reading time remaining</div>
                <div class="text-lg text-blue-600 font-medium mt-2" id="statusText">Take your time to read</div>
            </div>
        </div>

        <!-- ASSESSMENT INFO -->
        <div class="bg-gradient-to-r from-emerald-50 to-blue-50 rounded-3xl shadow-xl p-10 mb-16 border border-emerald-200">
            <h2 class="text-3xl font-bold text-emerald-800 mb-8 text-center">📋 Assessment Details</h2>
            <div class="grid md:grid-cols-2 gap-8 text-xl">
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-white rounded-2xl shadow-sm">
                        <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center mr-4">
                            <span class="text-2xl font-bold text-emerald-600">⏱️</span>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-2xl">{{ $assessment->duration_minutes }} minutes</div>
                            <div class="text-gray-600">Total time</div>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-white rounded-2xl shadow-sm">
                        <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mr-4">
                            <span class="text-2xl font-bold text-blue-600">❓</span>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-2xl">20 Questions</div>
                            <div class="text-gray-600">Multiple choice</div>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-white rounded-2xl shadow-sm">
                        <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center mr-4">
                            <span class="text-2xl font-bold text-amber-600">🎯</span>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-2xl">{{ $assessment->pass_percentage }}%</div>
                            <div class="text-gray-600">Required to pass</div>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-white rounded-2xl shadow-sm">
                        <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center mr-4">
                            <span class="text-2xl font-bold text-purple-600">💾</span>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-2xl">Auto-save</div>
                            <div class="text-gray-600">Every 10 seconds</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RULES -->
        <div class="grid lg:grid-cols-2 gap-12 mb-20">
            <!-- DO's -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border-4 border-green-100 hover:border-green-200 transition-all duration-300 group">
                <h2 class="text-4xl font-black text-green-800 mb-8 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                    <span class="w-5 h-5 bg-green-500 rounded-full mr-4 shadow-lg"></span>
                    ✅ DO's
                </h2>
                <ul class="space-y-6">
                    <li class="flex items-start group-hover:translate-x-2 transition-transform duration-300">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center font-bold text-xl mr-5 mt-1 shadow-md flex-shrink-0">✓</div>
                        <span class="text-xl leading-relaxed text-gray-800">Answer honestly based on your actual skills</span>
                    </li>
                    <li class="flex items-start group-hover:translate-x-2 transition-transform duration-300">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center font-bold text-xl mr-5 mt-1 shadow-md flex-shrink-0">✓</div>
                        <span class="text-xl leading-relaxed text-gray-800">Use only your knowledge (no external help)</span>
                    </li>
                    <li class="flex items-start group-hover:translate-x-2 transition-transform duration-300">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center font-bold text-xl mr-5 mt-1 shadow-md flex-shrink-0">✓</div>
                        <span class="text-xl leading-relaxed text-gray-800">Manage your time wisely (20 questions)</span>
                    </li>
                    <li class="flex items-start group-hover:translate-x-2 transition-transform duration-300">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center font-bold text-xl mr-5 mt-1 shadow-md flex-shrink-0">✓</div>
                        <span class="text-xl leading-relaxed text-gray-800">Your answers are auto-saved</span>
                    </li>
                </ul>
            </div>

            <!-- DON'Ts -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border-4 border-red-100 hover:border-red-200 transition-all duration-300 group">
                <h2 class="text-4xl font-black text-red-800 mb-8 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                    <span class="w-5 h-5 bg-red-500 rounded-full mr-4 shadow-lg"></span>
                    ❌ DON'Ts
                </h2>
                <ul class="space-y-6">
                    <li class="flex items-start group-hover:translate-x-2 transition-transform duration-300">
                        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center font-bold text-xl mr-5 mt-1 shadow-md flex-shrink-0">✗</div>
                        <span class="text-xl leading-relaxed text-gray-800">No Google/ChatGPT (3 violations = auto-submit)</span>
                    </li>
                    <li class="flex items-start group-hover:translate-x-2 transition-transform duration-300">
                        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center font-bold text-xl mr-5 mt-1 shadow-md flex-shrink-0">✗</div>
                        <span class="text-xl leading-relaxed text-gray-800">No tab switching (violation detected)</span>
                    </li>
                    <li class="flex items-start group-hover:translate-x-2 transition-transform duration-300">
                        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center font-bold text-xl mr-5 mt-1 shadow-md flex-shrink-0">✗</div>
                        <span class="text-xl leading-relaxed text-gray-800">Don't refresh/close during test</span>
                    </li>
                    <li class="flex items-start group-hover:translate-x-2 transition-transform duration-300">
                        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center font-bold text-xl mr-5 mt-1 shadow-md flex-shrink-0">✗</div>
                        <span class="text-xl leading-relaxed text-gray-800">No copy/paste/right-click allowed</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- START BUTTON -->
        <div class="text-center">
            <button id="startAssessment" 
                    class="px-16 py-8 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-2xl font-black rounded-3xl shadow-2xl hover:shadow-3xl transform hover:-translate-y-2 transition-all duration-500 inline-flex items-center text-lg tracking-wide pulse-glow">
                <svg class="w-8 h-8 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                🚀 START ASSESSMENT NOW
            </button>
            <p class="text-lg text-gray-600 mt-6 font-medium">Click to begin your {{ $assessment->duration_minutes }}-minute assessment</p>
        </div>
    </div>

    <script>
    let instructionTime = 120; // 2 minutes
    const assessmentId = {{ $assessment->id ?? 1 }};
    
    function updateInstructionTimer() {
        const minutes = Math.floor(instructionTime / 60);
        const seconds = instructionTime % 60;
        document.getElementById('instructionTimer').textContent = 
            `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        const statusEl = document.getElementById('statusText');
        const startBtn = document.getElementById('startAssessment');
        
        if (instructionTime <= 30) {
            statusEl.textContent = 'Time almost up! Ready to start?';
            statusEl.className = 'text-2xl font-semibold text-red-600 mt-2';
            startBtn.classList.add('animate-pulse');
        } else if (instructionTime <= 60) {
            statusEl.textContent = 'Halfway through. Keep reading!';
            statusEl.className = 'text-2xl font-semibold text-amber-600 mt-2';
        }
        
        if (instructionTime <= 0) {
            startBtn.innerHTML = `
                <svg class="w-8 h-8 mr-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v2m8 6h-2m-8 6v-2M4 12H2m15.364-5.636l-.707.707M6.343 17.657l-.707-.707"/>
                </svg>
                STARTING IN 5 SECONDS...
            `;
            setTimeout(() => {
                window.location.href = '/assessment/start';
            }, 5000);
            return;
        }
        instructionTime--;
    }
    
    // Start instruction timer
    setInterval(updateInstructionTimer, 1000);
    updateInstructionTimer();
    
    // Manual start button
    document.getElementById('startAssessment').onclick = function(e) {
        e.preventDefault();
        this.innerHTML = `
            <svg class="w-8 h-8 mr-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v2m8 6h-2m-8 6v-2M4 12H2m15.364-5.636l-.707.707M6.343 17.657l-.707-.707"/>
            </svg>
            STARTING SOON...
        `;
        setTimeout(() => {
            window.location.href = '/assessment/start';
        }, 1000);
    };
    
    // Prevent right-click, F12, etc.
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('keydown', e => {
        if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
            e.preventDefault();
        }
    });
    </script>
</body>
</html>

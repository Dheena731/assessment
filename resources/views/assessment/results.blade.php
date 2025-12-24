<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen p-8">
    <div class="container mx-auto max-w-4xl">
        <div class="bg-white rounded-xl shadow-2xl p-8 text-center">
            <!-- RESULT HEADER -->
            @if($isPassed)
            <div class="mb-8">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-green-600 mb-4">🎉 PASSED!</h1>
                <p class="text-xl text-gray-700">Excellent job! You have successfully completed the assessment.</p>
            </div>
            @else
            <div class="mb-8">
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-16 h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-red-600 mb-4">Try Again!</h1>
                <p class="text-xl text-gray-700">Keep practicing and you'll get there!</p>
            </div>
            @endif

            <!-- SCORE -->
            <div class="bg-gradient-to-r {{ $isPassed ? 'from-green-50 to-green-100' : 'from-red-50 to-red-100' }} rounded-2xl p-8 mb-8">
                <h2 class="text-5xl font-bold text-gray-800 mb-2">
                    {{ $userAssessment->score }} / {{ count($userAssessment->selected_questions) }}
                </h2>
                <div class="text-2xl font-semibold {{ $isPassed ? 'text-green-700' : 'text-red-700' }}">
                    {{ number_format($percentage, 1) }}%
                </div>
                <div class="text-sm text-gray-500 mt-2">
                    Need {{ $assessment->pass_percentage }}% to pass
                </div>
            </div>

            <!-- ASSESSMENT INFO -->
            <div class="grid md:grid-cols-2 gap-6 mb-8 text-left">
                <div>
                    <h3 class="font-bold text-lg mb-2 text-gray-800">Assessment Details</h3>
                    <p><span class="font-semibold">Name:</span> {{ $assessment->name }}</p>
                    <p><span class="font-semibold">Duration:</span> {{ $assessment->duration_minutes }} minutes</p>
                    <p><span class="font-semibold">Completed:</span> {{ $userAssessment->ended_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-2 text-gray-800">Performance</h3>
                    <p><span class="font-semibold">Correct:</span> {{ $userAssessment->score }} answers</p>
                    <p><span class="font-semibold">Total:</span> {{ count($userAssessment->selected_questions) }} questions</p>
                    <p><span class="font-semibold">Status:</span> 
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $isPassed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $isPassed ? 'PASSED' : 'FAILED' }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/assessment/start" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl text-lg transition-all text-center flex-1">
                    🔄 Take Again
                </a>
                <a href="/resume-upload" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-xl text-lg transition-all text-center flex-1">
                    ← Back to Dashboard
                </a>
            </div>

            <!-- CERTIFICATE TEASER (Day 4) -->
            @if($isPassed)
            <div class="mt-8 p-6 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl border-2 border-dashed border-yellow-200">
                <h3 class="text-xl font-bold text-yellow-800 mb-2">📜 Certificate Ready!</h3>
                <p class="text-yellow-700 mb-4">Download your completion certificate (Coming in Day 4)</p>
                <button class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-xl transition-all disabled:opacity-50" disabled>
                    Download Certificate
                </button>
            </div>
            @endif
        </div>
    </div>
</body>
</html>

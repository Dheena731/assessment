{{-- resources/views/pdf/report.blade.php - SIMPLIFIED VERSION --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Assessment Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif !important; }
        @page { margin: 1in; }
    </style>
</head>
<body class="bg-white text-gray-800">
    <div class="max-w-4xl mx-auto p-8 md:p-12">
        <!-- HEADER -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-black text-blue-900 mb-6">📊 ASSESSMENT REPORT</h1>
            <div class="bg-gradient-to-r from-slate-50 to-blue-50 p-8 rounded-3xl shadow-lg border">
                <div class="grid md:grid-cols-2 gap-6 text-lg">
                    <div><strong>Candidate:</strong><br>{{ $user->name }}</div>
                    <div><strong>Assessment:</strong><br>{{ $assessment->name }}</div>
                    <div><strong>Email:</strong><br>{{ $user->email }}</div>
                    <div><strong>Date:</strong><br>{{ now()->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        </div>

        <!-- SCORE -->
        <div class="text-center mb-16">
            <div class="inline-block p-1 rounded-full bg-gradient-to-r {{ $isPassed ? 'from-emerald-400 to-green-500' : 'from-red-400 to-rose-500' }} mb-8">
                <div class="w-64 h-64 md:w-72 md:h-72 rounded-full flex flex-col items-center justify-center bg-white shadow-2xl border-8 {{ $isPassed ? 'border-emerald-400' : 'border-red-400' }}">
                    <div class="text-5xl md:text-6xl font-black {{ $isPassed ? 'text-emerald-600' : 'text-red-600' }} mb-2">
                        {{ $score }} / {{ $totalQuestions }}
                    </div>
                    <div class="text-7xl md:text-8xl font-black {{ $isPassed ? 'text-emerald-500' : 'text-red-500' }}">
                        {{ number_format($percentage, 1) }}%
                    </div>
                </div>
            </div>
            <div class="text-4xl md:text-5xl font-black mb-4 {{ $isPassed ? 'text-emerald-700' : 'text-red-700' }}">
                {{ $isPassed ? '✓ PASSED' : '✗ FAILED' }}
            </div>
            <div class="text-xl text-gray-600 mb-4">Passing Score: {{ $assessment->pass_percentage }}%</div>
        </div>

        <!-- STATS -->
        <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-3xl p-8 mb-12 shadow-xl">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">📈 Performance Summary</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-md text-center">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-emerald-50 rounded-xl">
                            <div class="text-3xl font-bold text-emerald-600">{{ $score }}</div>
                            <div class="text-sm text-emerald-700">Correct</div>
                        </div>
                        <div class="p-4 bg-red-50 rounded-xl">
                            <div class="text-3xl font-bold text-red-600">{{ $totalQuestions - $score }}</div>
                            <div class="text-sm text-red-700">Wrong</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-md">
                    <div class="space-y-4 text-lg">
                        <div><strong>Duration:</strong> {{ $assessment->duration_minutes }} min</div>
                        <div><strong>Completed:</strong> {{ $userAssessment->ended_at->format('M d, Y h:i A') }}</div>
                        <div><strong>Score:</strong> {{ number_format($percentage, 1) }}%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RECOMMENDATIONS -->
        <div class="bg-gradient-to-r {{ $isPassed ? 'from-emerald-50 to-green-50' : 'from-red-50 to-rose-50' }} rounded-3xl p-10 shadow-2xl border-4 {{ $isPassed ? 'border-emerald-200' : 'border-red-200' }}">
            <h3 class="text-3xl font-black mb-8 text-center {{ $isPassed ? 'text-emerald-800' : 'text-red-800' }}">💡 Next Steps</h3>
            @if($isPassed)
                <div class="max-w-2xl mx-auto text-center text-xl">
                    <p class="font-bold mb-8">🎉 Passed with {{ number_format($percentage, 1) }}%</p>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="bg-white p-6 rounded-2xl shadow-md">✅ Practice regularly</div>
                        <div class="bg-white p-6 rounded-2xl shadow-md">🎯 Advanced topics</div>
                        <div class="bg-white p-6 rounded-2xl shadow-md">📈 Share success</div>
                    </div>
                </div>
            @else
                <div class="max-w-2xl mx-auto text-center text-xl">
                    <p class="font-bold mb-8">⚠️ {{ number_format($assessment->pass_percentage - $percentage, 1) }}% from passing</p>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="bg-white p-6 rounded-2xl shadow-md">📖 Review mistakes</div>
                        <div class="bg-white p-6 rounded-2xl shadow-md">🎯 Study weak areas</div>
                        <div class="bg-white p-6 rounded-2xl shadow-md">🔄 Retake soon</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>

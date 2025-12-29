<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Resume Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-2xl">
        {{-- Header --}}
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Resume Analysis</h1>
                <p class="text-gray-500 text-sm mt-1">Step 2 of 2</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="text-gray-500 hover:text-red-600 text-sm font-medium transition-colors flex items-center gap-1"
                        aria-label="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>

        {{-- Status Card --}}
        <div class="mb-8">
            @if($resume->is_valid)
                {{-- Valid Status --}}
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-green-500 rounded-full p-3">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-green-800">Congratulations!</h2>
                                <p class="text-green-700 text-sm">Your resume meets the requirements</p>
                            </div>
                        </div>
                        
                        <div class="bg-white/50 backdrop-blur-sm rounded-xl p-4 mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Skill Score</span>
                                <span class="text-2xl font-bold text-green-700">{{ $resume->skill_score }} / 200</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div id="progress-bar-valid" class="bg-gradient-to-r from-green-500 to-emerald-500 h-full rounded-full transition-all duration-1000 ease-out" 
                                     data-width="{{ min(100, ($resume->skill_score / 200) * 100) }}" style="width: 0%"></div>
                            </div>
                            <p class="text-xs text-gray-600 mt-2">
                                Threshold: {{ $resume->threshold_score }} points
                                <span class="text-green-600 font-semibold ml-1">
                                    (+{{ $resume->skill_score - $resume->threshold_score }} above minimum)
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @else
                {{-- Invalid Status --}}
                <div class="bg-gradient-to-br from-red-50 to-orange-50 border-2 border-red-200 rounded-2xl p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-red-500 rounded-full p-3">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-red-800">Requirements Not Met</h2>
                                <p class="text-red-700 text-sm">Your resume needs more relevant skills</p>
                            </div>
                        </div>
                        
                        <div class="bg-white/50 backdrop-blur-sm rounded-xl p-4 mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Skill Score</span>
                                <span class="text-2xl font-bold text-red-700">{{ $resume->skill_score }} / 200</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div id="progress-bar-invalid" class="bg-gradient-to-r from-red-500 to-orange-500 h-full rounded-full transition-all duration-1000 ease-out" 
                                     data-width="{{ min(100, ($resume->skill_score / 200) * 100) }}" style="width: 0%"></div>
                            </div>
                            <p class="text-xs text-gray-600 mt-2">
                                Threshold: {{ $resume->threshold_score }} points
                                <span class="text-red-600 font-semibold ml-1">
                                    ({{ max(0, $resume->threshold_score - $resume->skill_score) }} points needed)
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Matched Skills Section --}}
        @php
            $skills = [];
            if($resume->matched_skills) {
                $skills = is_array($resume->matched_skills) 
                    ? $resume->matched_skills 
                    : json_decode($resume->matched_skills, true) ?? [];
            }
        @endphp

        @if(!empty($skills))
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Detected Skills
                    </h3>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ count($skills) }} {{ Str::plural('skill', count($skills)) }}
                    </span>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <div class="flex flex-wrap gap-2">
                        @foreach($skills as $skill)
                            <div class="bg-white border-2 border-blue-200 text-blue-800 px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:shadow-md transition-shadow flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ ucfirst($skill['name']) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="mb-8 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-yellow-800 text-sm font-medium">No skills detected in your resume.</p>
                        <p class="text-yellow-700 text-xs mt-1">Make sure your resume includes relevant technical skills and experience.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="space-y-3">
            @if($resume->is_valid)
                <a href="{{ route('assessment.instructions') }}" 
                   class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6 rounded-xl font-semibold text-center block hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span>Start Assessment</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Assessment Details</p>
                            <ul class="text-xs text-blue-800 mt-1 space-y-1">
                                <li>• 20 multiple choice questions</li>
                                <li>• 15 minutes time limit</li>
                                <li>• Questions based on your detected skills</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('resume.upload') }}" 
                   class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6 rounded-xl font-semibold text-center block hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span>Upload New Resume</span>
                </a>
                
                <div class="bg-orange-50 rounded-xl p-4 border border-orange-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-orange-900">Tips to Improve</p>
                            <ul class="text-xs text-orange-800 mt-1 space-y-1">
                                <li>• Include more technical skills relevant to the role</li>
                                <li>• Add certifications and projects to your resume</li>
                                <li>• Ensure skills are clearly listed and detailed</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-xs text-gray-500 text-center">
                Questions? <a href="{{ url('/contact') }}" class="text-blue-600 hover:text-blue-700 font-medium">Contact Support</a>
            </p>
        </div>
    </div>

    <script>
        // Animate progress bar on page load
        document.addEventListener('DOMContentLoaded', function() {
            const progressBarValid = document.getElementById('progress-bar-valid');
            const progressBarInvalid = document.getElementById('progress-bar-invalid');
            
            const progressBar = progressBarValid || progressBarInvalid;
            
            if (progressBar) {
                const targetWidth = progressBar.getAttribute('data-width');
                setTimeout(() => {
                    progressBar.style.width = targetWidth + '%';
                }, 100);
            }
        });
    </script>
</body>
</html>
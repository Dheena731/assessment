<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Upload Resume</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md">
        {{-- Header --}}
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Upload Resume</h1>
                <p class="text-gray-500 text-sm mt-1">Step 1 of 2</p>
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

        <p class="text-gray-600 text-sm mb-8">
            Upload your resume to continue with the assessment. We accept PDF files up to 10MB.
        </p>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg" role="alert">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Error Display --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg" role="alert">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        @foreach ($errors->all() as $error)
                            <p class="text-red-700 text-sm font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Upload Form --}}
        <form method="POST" action="{{ route('resume.upload.store') }}" enctype="multipart/form-data" id="uploadForm">
            @csrf
            
            {{-- Drag and Drop Zone --}}
            <div class="mb-6">
                <label for="resume-input" class="block text-sm font-semibold text-gray-700 mb-3">
                    Resume File
                </label>
                
                <div class="relative">
                    <input type="file" 
                           id="resume-input"
                           name="resume" 
                           accept="application/pdf" 
                           required
                           class="hidden"
                           onchange="handleFileSelect(event)">
                    
                    <label for="resume-input" 
                           id="drop-zone"
                           class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all group">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-prompt">
                            <svg class="w-12 h-12 mb-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-600">
                                <span class="font-semibold">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500">PDF files only (Max 10MB)</p>
                        </div>
                        
                        {{-- File Selected State --}}
                        <div id="file-info" class="hidden flex items-center gap-3 p-4">
                            <svg class="w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-left flex-1">
                                <p id="file-name" class="text-sm font-medium text-gray-700"></p>
                                <p id="file-size" class="text-xs text-gray-500"></p>
                            </div>
                            <button type="button" onclick="clearFile()" class="text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" 
                    id="submit-btn"
                    class="w-full bg-blue-600 text-white py-3.5 px-4 rounded-xl font-semibold hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <span id="btn-text">Continue to Assessment</span>
                <svg id="btn-arrow" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
                <svg id="btn-spinner" class="hidden animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </form>

        {{-- Help Text --}}
        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-xs text-gray-500 text-center">
                Need help? <a href="{{ url('/contact') }}" class="text-blue-600 hover:text-blue-700 font-medium">Contact Support</a>
            </p>
        </div>
    </div>

    <script>
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('resume-input');
        const uploadPrompt = document.getElementById('upload-prompt');
        const fileInfo = document.getElementById('file-info');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const submitBtn = document.getElementById('submit-btn');
        const uploadForm = document.getElementById('uploadForm');

        // Drag and drop handlers
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('border-blue-500', 'bg-blue-50');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            });
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            // Validate file type
            if (files[0] && files[0].type === 'application/pdf') {
                fileInput.files = files;
                handleFileSelect({ target: fileInput });
            } else {
                alert('Please upload a PDF file only.');
            }
        });

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file type
                if (file.type !== 'application/pdf') {
                    alert('Please upload a PDF file only.');
                    clearFile();
                    return;
                }
                
                // Validate file size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert('File size must be less than 10MB.');
                    clearFile();
                    return;
                }
                
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                uploadPrompt.classList.add('hidden');
                fileInfo.classList.remove('hidden');
                fileInfo.classList.add('flex');
                dropZone.classList.add('border-blue-500', 'bg-blue-50');
            }
        }

        function clearFile() {
            fileInput.value = '';
            uploadPrompt.classList.remove('hidden');
            fileInfo.classList.add('hidden');
            fileInfo.classList.remove('flex');
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Form submission with loading state
        uploadForm.addEventListener('submit', (e) => {
            const file = fileInput.files[0];
            
            // Final validation before submit
            if (!file) {
                e.preventDefault();
                alert('Please select a file to upload.');
                return;
            }
            
            if (file.type !== 'application/pdf') {
                e.preventDefault();
                alert('Please upload a PDF file only.');
                return;
            }
            
            if (file.size > 10 * 1024 * 1024) {
                e.preventDefault();
                alert('File size must be less than 10MB.');
                return;
            }
            
            submitBtn.disabled = true;
            document.getElementById('btn-text').textContent = 'Uploading...';
            document.getElementById('btn-arrow').classList.add('hidden');
            document.getElementById('btn-spinner').classList.remove('hidden');
        });
    </script>
</body>
</html>
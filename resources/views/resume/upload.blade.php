<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Resume</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">

        <h1 class="text-2xl font-bold mb-2 text-center">
            Resume Upload
        </h1>

        <p class="text-gray-600 text-sm text-center mb-6">
            Upload your resume to continue the assessment
        </p>

        <!-- Dummy Upload Form -->
        <form>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Select Resume (PDF / DOC)
                </label>
                <input type="file"
                       class="w-full border rounded p-2 cursor-not-allowed"
                       disabled>
            </div>

            <button type="button"
                    class="w-full bg-blue-600 text-white py-2 rounded opacity-60 cursor-not-allowed">
                Upload (Coming Soon)
            </button>
        </form>

        <p class="text-xs text-gray-400 text-center mt-4">
            * This is a demo page. Upload functionality will be enabled soon.
        </p>

    </div>

</body>
</html>

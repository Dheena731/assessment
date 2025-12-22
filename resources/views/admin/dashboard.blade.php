<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Top Navbar -->
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">
            Assessment Dashboard
        </h1>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm text-red-600 hover:underline">
                Logout
            </button>
        </form>
    </nav>

    <!-- Main Content -->
    <div class="p-6 max-w-6xl mx-auto">

        <!-- Welcome Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-2">
                Welcome, {{ auth()->user()->name ?? 'User' }} 👋
            </h2>
            <p class="text-gray-600">
                You are successfully logged in using Google.
            </p>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Resume Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Resume</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Upload or manage your resume.
                </p>
                <a href="{{ route('resume.upload') }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded text-sm">
                    Upload Resume
                </a>
            </div>

            <!-- Assessment Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Assessment</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Start your assessment once enabled.
                </p>
                <button disabled
                        class="bg-gray-400 text-white px-4 py-2 rounded text-sm cursor-not-allowed">
                    Coming Soon
                </button>
            </div>

            <!-- Profile Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Profile</h3>
                <p class="text-sm text-gray-600 mb-4">
                    View your account details.
                </p>
                <button disabled
                        class="bg-gray-400 text-white px-4 py-2 rounded text-sm cursor-not-allowed">
                    View Profile
                </button>
            </div>

        </div>

    </div>

</body>
</html>

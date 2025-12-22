<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    {{-- Tailwind CDN (for quick styling) --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md text-center">

        <h1 class="text-2xl font-bold mb-2">Welcome Back</h1>
        <p class="text-gray-600 mb-6">
            Sign in using your Google account
        </p>

        <a href="{{ url('/auth/google') }}"
           class="flex items-center justify-center gap-3 border border-gray-300 rounded-lg px-4 py-3 hover:bg-gray-50 transition">

            <img
                src="https://developers.google.com/identity/images/g-logo.png"
                alt="Google"
                class="w-5 h-5"
            />

            <span class="font-medium text-gray-700">
                Continue with Google
            </span>
        </a>

        {{-- Error Message --}}
        @if (session('error'))
            <p class="text-red-500 text-sm mt-4">
                {{ session('error') }}
            </p>
        @endif

    </div>

</body>
</html>

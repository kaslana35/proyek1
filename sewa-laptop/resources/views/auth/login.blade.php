<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SewaLaptop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-slate-900 dark:to-slate-800 flex items-center justify-center min-h-screen p-4">
    <!-- Dark Mode Toggle -->
    <button onclick="toggleTheme()" class="fixed top-4 right-4 p-2.5 rounded-xl bg-white/80 dark:bg-slate-800/80 backdrop-blur-md text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-slate-700 shadow-sm border border-gray-200 dark:border-slate-700 transition-smooth z-50" title="Toggle Dark Mode">
        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
    </button>

    <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700 w-full max-w-md">
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">Masuk ke Akun</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">SewaLaptop Enterprise Core</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Email:</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 transition-smooth" required autofocus>
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Password:</label>
                <div class="relative">
                    <input type="password" name="password" id="password" class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 pr-10 transition-smooth" required>
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-xs font-semibold transition-smooth">
                        <span id="toggleText">Show</span>
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-between mb-6 text-xs">
                <label class="flex items-center text-gray-600 dark:text-gray-400">
                    <input type="checkbox" name="remember" class="mr-2 rounded border-gray-300 dark:border-slate-600"> Ingat saya
                </label>
                <a href="{{ route('password.request') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Lupa password?</a>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2.5 px-4 rounded-xl hover:bg-blue-700 transition-smooth shadow-sm text-sm">Masuk</button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">Buat akun</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleText = document.getElementById('toggleText');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleText.textContent = 'Hide';
            } else {
                passwordInput.type = 'password';
                toggleText.textContent = 'Show';
            }
        }
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>
</body>
</html>
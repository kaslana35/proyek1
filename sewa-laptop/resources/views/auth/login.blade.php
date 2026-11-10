<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SewaLaptop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 w-full max-w-md">
        <div class="text-center mb-6">
            <div class="mx-auto w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Masuk ke Akun</h2>
            <p class="text-sm text-gray-500 mt-1">SewaLaptop</p>
        </div>
        
        @if(session('success'))
            <div class="bg-blue-50 border border-blue-200 text-blue-600 px-4 py-3 rounded-xl mb-4 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-4 text-sm font-medium">
                {{ $errors->first('email') ?: $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Email:</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required autofocus>
            </div>
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Password:</label>
                <div class="relative">
                    <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm pr-10" required>
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 text-xs font-semibold">
                        <span id="toggleText">Show</span>
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-between mb-6 text-xs">
                <label class="flex items-center text-gray-600">
                    <input type="checkbox" name="remember" class="mr-2 rounded border-gray-300"> Ingat saya
                </label>
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline font-medium">Lupa password?</a>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2.5 px-4 rounded-xl hover:bg-blue-700 transition shadow-sm text-sm">Masuk</button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Buat akun</a>
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
    </script>
</body>
</html>

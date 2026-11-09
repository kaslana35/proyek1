<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SewaLaptop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen py-12">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 w-full max-w-md">
        <div class="text-center mb-6">
            <div class="mx-auto w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Buat Akun Baru</h2>
            <p class="text-sm text-gray-500 mt-1">SewaLaptop</p>
        </div>
        
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-4 text-sm font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Lengkap:</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required autofocus>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Email:</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Password:</label>
                <input type="password" name="password" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Konfirmasi Password:</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2.5 px-4 rounded-xl hover:bg-blue-700 transition shadow-sm text-sm">Daftar & Masuk</button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Masuk di sini</a>
        </div>
    </div>
</body>
</html>

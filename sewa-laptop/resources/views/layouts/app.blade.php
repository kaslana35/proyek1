<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SewaLaptop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex flex-wrap justify-between items-center">
            <a href="{{ route('dashboard') }}" class="font-bold text-xl">💻 SewaLaptop</a>
            
            <div class="flex items-center gap-6 text-sm">
                @role('admin')
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-300 {{ request()->routeIs('dashboard') ? 'text-blue-400' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.laptops.manage') }}" class="hover:text-blue-300 {{ request()->routeIs('admin.laptops.manage') ? 'text-blue-400' : '' }}">Produk</a>
                    <a href="{{ route('admin.users.index') }}" class="hover:text-blue-300 {{ request()->routeIs('admin.users.index') ? 'text-blue-400' : '' }}">User</a>
                @endrole
                @role('user')
                    <a href="{{ route('laptops.index') }}" class="hover:text-blue-300 {{ request()->routeIs('laptops.index') ? 'text-blue-400' : '' }}">Katalog</a>
                    <a href="{{ route('transactions.index') }}" class="hover:text-blue-300 {{ request()->routeIs('transactions.index') ? 'text-blue-400' : '' }}">Riwayat</a>
                @endrole
                
                <div class="border-l border-gray-600 pl-6 flex items-center gap-5">
                    <!-- Profil Icon -->
                    <a href="{{ route('profile.edit') }}" title="Profil" class="text-white hover:text-blue-400 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>

                    <!-- Logout Icon -->
                    <form action="/logout" method="POST" class="flex items-center">
                        @csrf
                        <button type="submit" title="Logout" class="text-red-400 hover:text-red-300 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mx-auto mt-6 px-4">
        @yield('content')
    </div>
</body>
</html>

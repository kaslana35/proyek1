<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SewaLaptop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen pb-16 md:pb-0">
    <nav class="bg-blue-600 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('laptops.index') }}" class="font-bold text-lg tracking-tight text-white flex items-center gap-2">
                <div class="bg-white p-1.5 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <span>SEWALAPTOP</span>
            </a>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-6 text-sm">
                @auth
                    @role('admin')
                        <a href="{{ route('dashboard') }}" class="hover:text-blue-200 {{ request()->routeIs('dashboard') ? 'font-bold underline' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.stok.manage') }}" class="hover:text-blue-200 {{ request()->routeIs('admin.stok.*') ? 'font-bold underline' : '' }}">Stok</a>
                        <a href="{{ route('admin.produk.manage') }}" class="hover:text-blue-200 {{ request()->routeIs('admin.produk.*') ? 'font-bold underline' : '' }}">Produk</a>
                        <a href="{{ route('admin.inspections.index') }}" class="hover:text-blue-200 {{ request()->routeIs('admin.inspections.*') ? 'font-bold underline' : '' }}">Pemeriksaan</a>
                        <a href="{{ route('admin.transactions.index') }}" class="hover:text-blue-200 {{ request()->routeIs('admin.transactions.index') ? 'font-bold underline' : '' }}">Transaksi</a>
                        <a href="{{ route('admin.users.index') }}" class="hover:text-blue-200 {{ request()->routeIs('admin.users.index') ? 'font-bold underline' : '' }}">User</a>
                    @endrole
                    @role('user')
                        <a href="{{ route('laptops.index') }}" class="hover:text-blue-200 {{ request()->routeIs('laptops.index') ? 'font-bold underline' : '' }}">Katalog</a>
                        <a href="{{ route('transactions.index') }}" class="hover:text-blue-200 {{ request()->routeIs('transactions.index') ? 'font-bold underline' : '' }}">Riwayat</a>
                    @endrole
                    
                    <div class="border-l border-blue-400 pl-6 flex items-center gap-5">
                        <a href="{{ route('profile.edit') }}" title="Profil" class="text-white hover:text-blue-200 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </a>
                        <form action="/logout" method="POST" class="flex items-center">
                            @csrf
                            <button type="submit" title="Logout" class="text-red-200 hover:text-white transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('laptops.index') }}" class="hover:text-blue-200 {{ request()->routeIs('laptops.index') ? 'font-bold underline' : '' }}">Katalog</a>
                    <a href="{{ route('login') }}" class="bg-white text-blue-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-50 transition shadow-sm">Login</a>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="md:hidden text-white focus:outline-none p-1">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobileMenu" class="hidden md:hidden bg-blue-700 px-4 pt-2 pb-4 space-y-2 border-t border-blue-500">
            @auth
                @role('admin')
                    <a href="{{ route('dashboard') }}" class="block py-2 hover:bg-blue-600 px-3 rounded-lg text-sm">Dashboard</a>
                    <a href="{{ route('admin.stok.manage') }}" class="block py-2 hover:bg-blue-600 px-3 rounded-lg text-sm">Stok</a>
                    <a href="{{ route('admin.produk.manage') }}" class="block py-2 hover:bg-blue-600 px-3 rounded-lg text-sm">Produk</a>
                    <a href="{{ route('admin.inspections.index') }}" class="block py-2 hover:bg-blue-600 px-3 rounded-lg text-sm">Pemeriksaan</a>
                    <a href="{{ route('admin.transactions.index') }}" class="block py-2 hover:bg-blue-600 px-3 rounded-lg text-sm">Transaksi</a>
                    <a href="{{ route('admin.users.index') }}" class="block py-2 hover:bg-blue-600 px-3 rounded-lg text-sm">User</a>
                @endrole
                @role('user')
                    <a href="{{ route('laptops.index') }}" class="block py-2 hover:bg-blue-600 px-3 rounded-lg text-sm">Katalog</a>
                    <a href="{{ route('transactions.index') }}" class="block py-2 hover:bg-blue-600 px-3 rounded-lg text-sm">Riwayat</a>
                @endrole
                <a href="{{ route('profile.edit') }}" class="block py-2 hover:bg-blue-600 px-3 rounded-lg text-sm">Profil Saya</a>
                <form action="/logout" method="POST" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full text-left bg-red-500 text-white py-2 px-3 rounded-lg text-sm font-semibold">Logout</button>
                </form>
            @else
                <a href="{{ route('laptops.index') }}" class="block py-2 hover:bg-blue-600 px-3 rounded-lg text-sm">Katalog</a>
                <a href="{{ route('login') }}" class="block text-center mt-3 bg-white text-blue-600 py-2 rounded-xl text-sm font-bold shadow-sm">Login</a>
            @endauth
        </div>
    </nav>

    <div class="container mx-auto mt-6 px-4 mb-12">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl mb-6 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
                {{ $errors->first() }}
            </div>
        @endif
        @yield('content')
    </div>
</body>
</html>

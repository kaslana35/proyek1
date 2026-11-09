<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SewaLaptop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex flex-wrap justify-between items-center">
            <a href="{{ route('laptops.index') }}" class="font-bold text-xl tracking-tight text-white flex items-center gap-2">
                <div class="bg-white p-1.5 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                SEWALAPTOP
            </a>
            
            <div class="flex items-center gap-6 text-sm">
                @auth
                    @role('admin')
                        <a href="{{ route('dashboard') }}" class="hover:text-blue-300 {{ request()->routeIs('dashboard') ? 'text-blue-400' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.stok.manage') }}" class="hover:text-blue-300 {{ request()->routeIs('admin.stok.*') ? 'text-blue-400' : '' }}">Stok</a>
                        <a href="{{ route('admin.produk.manage') }}" class="hover:text-blue-300 {{ request()->routeIs('admin.produk.*') ? 'text-blue-400' : '' }}">Produk</a>
                        <a href="{{ route('admin.inspections.index') }}" class="hover:text-blue-300 {{ request()->routeIs('admin.inspections.*') ? 'text-blue-400' : '' }}">Pemeriksaan</a>
                        <a href="{{ route('admin.transactions.index') }}" class="hover:text-blue-300 {{ request()->routeIs('admin.transactions.index') ? 'text-blue-400' : '' }}">Transaksi</a>
                        <a href="{{ route('admin.users.index') }}" class="hover:text-blue-300 {{ request()->routeIs('admin.users.index') ? 'text-blue-400' : '' }}">User</a>
                    @endrole
                    @role('user')
                        <a href="{{ route('laptops.index') }}" class="hover:text-blue-300 {{ request()->routeIs('laptops.index') ? 'text-blue-400' : '' }}">Katalog</a>
                        <a href="{{ route('transactions.index') }}" class="hover:text-blue-300 {{ request()->routeIs('transactions.index') ? 'text-blue-400' : '' }}">Riwayat</a>
                    @endrole
                    
                    <div class="border-l border-gray-600 pl-6 flex items-center gap-5">
                        <a href="{{ route('profile.edit') }}" title="Profil" class="text-white hover:text-blue-400 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </a>
                        <form action="/logout" method="POST" class="flex items-center">
                            @csrf
                            <button type="submit" title="Logout" class="text-red-400 hover:text-red-300 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('laptops.index') }}" class="hover:text-blue-300 {{ request()->routeIs('laptops.index') ? 'text-blue-400' : '' }}">Katalog</a>
                    <a href="{{ route('login') }}" class="bg-blue-600 px-4 py-2 rounded-lg text-white font-bold hover:bg-blue-700 transition">Login</a>
                @endauth
            </div>
        </div>
    </nav>
    <div class="container mx-auto mt-6 px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif
        @yield('content')
    </div>
</body>
</html>

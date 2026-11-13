<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SewaLaptop</title>
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
                    },
                    colors: {
                        primary: '#2563EB',
                        accent: '#EA580C',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
        * { cursor: pointer; }
        a, button, [role="button"], input[type="submit"], select, label[for] { cursor: pointer; }
        input, textarea { cursor: text; }
        .transition-smooth { transition: all 200ms ease-in-out; }
        /* Dark mode scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
    </style>
    <script>
        // Dark mode toggle
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="bg-gray-50 dark:bg-slate-900 min-h-screen pb-16 md:pb-0 transition-colors duration-300">
    <nav class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md text-gray-800 dark:text-white shadow-sm sticky top-0 z-50 border-b border-gray-200 dark:border-slate-700 transition-colors duration-300">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('laptops.index') }}" class="font-bold text-lg tracking-tight flex items-center gap-2.5 hover:opacity-80 transition-smooth">
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-1.5 rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-gray-900 dark:text-white">Sewa Laptop</span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-6 text-sm">
                @auth
                    @role('user')
                        @php
                            $overdueCount = \App\Models\Transaction::where('user_id', auth()->id())
                                ->where('status', 'approved')
                                ->where('tanggal_selesai', '<', now())
                                ->count();
                        @endphp
                        <a href="{{ route('laptops.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 {{ request()->routeIs('laptops.index') ? 'font-bold text-blue-600 dark:text-blue-400' : '' }} transition-smooth">Katalog</a>
                        <a href="{{ route('transactions.index') }}" class="relative text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 {{ request()->routeIs('transactions.index') ? 'font-bold text-blue-600 dark:text-blue-400' : '' }} transition-smooth">
                            Riwayat
                            @if($overdueCount > 0)
                                <span class="absolute -top-2 -right-3 bg-red-500 text-white text-[10px] w-4 h-4 flex items-center justify-center rounded-full font-bold">{{ $overdueCount }}</span>
                            @endif
                        </a>
                    @endrole
                    @role('admin')
                        <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 {{ request()->routeIs('dashboard') ? 'font-bold text-blue-600 dark:text-blue-400' : '' }} transition-smooth">Dashboard</a>
                        <a href="{{ route('admin.stok.manage') }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 {{ request()->routeIs('admin.stok.*') ? 'font-bold text-blue-600 dark:text-blue-400' : '' }} transition-smooth">Stok</a>
                        <a href="{{ route('admin.produk.manage') }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 {{ request()->routeIs('admin.produk.*') ? 'font-bold text-blue-600 dark:text-blue-400' : '' }} transition-smooth">Produk</a>
                        <a href="{{ route('admin.inspections.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 {{ request()->routeIs('admin.inspections.*') ? 'font-bold text-blue-600 dark:text-blue-400' : '' }} transition-smooth">Pemeriksaan</a>
                        <a href="{{ route('admin.transactions.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 {{ request()->routeIs('admin.transactions.index') ? 'font-bold text-blue-600 dark:text-blue-400' : '' }} transition-smooth">Transaksi</a>
                        <a href="{{ route('admin.users.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 {{ request()->routeIs('admin.users.index') ? 'font-bold text-blue-600 dark:text-blue-400' : '' }} transition-smooth">User</a>
                    @endrole

                    <div class="border-l border-gray-300 dark:border-slate-600 pl-6 flex items-center gap-4">
                        <!-- Dark Mode Toggle -->
                        <button onclick="toggleTheme()" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition-smooth" title="Toggle Dark Mode">
                            <svg id="sunIcon" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <svg id="moonIcon" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        </button>
                        <a href="{{ route('profile.edit') }}" title="Profil" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-smooth">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </a>
                        <form action="/logout" method="POST" class="flex items-center">
                            @csrf
                            <button type="submit" title="Logout" class="text-red-400 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 transition-smooth">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('laptops.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 {{ request()->routeIs('laptops.index') ? 'font-bold text-blue-600 dark:text-blue-400' : '' }} transition-smooth">Katalog</a>
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-700 transition-smooth shadow-sm">Login</a>
                    <!-- Dark Mode Toggle (Guest) -->
                    <button onclick="toggleTheme()" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition-smooth" title="Toggle Dark Mode">
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="md:hidden text-gray-600 dark:text-gray-300 focus:outline-none p-1">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobileMenu" class="hidden md:hidden bg-white dark:bg-slate-800 px-4 pt-2 pb-4 space-y-2 border-t border-gray-200 dark:border-slate-700">
            @auth
                @role('admin')
                    <a href="{{ route('dashboard') }}" class="block py-2 hover:bg-gray-100 dark:hover:bg-slate-700 px-3 rounded-lg text-sm text-gray-700 dark:text-gray-300">Dashboard</a>
                    <a href="{{ route('admin.stok.manage') }}" class="block py-2 hover:bg-gray-100 dark:hover:bg-slate-700 px-3 rounded-lg text-sm text-gray-700 dark:text-gray-300">Stok</a>
                    <a href="{{ route('admin.produk.manage') }}" class="block py-2 hover:bg-gray-100 dark:hover:bg-slate-700 px-3 rounded-lg text-sm text-gray-700 dark:text-gray-300">Produk</a>
                    <a href="{{ route('admin.inspections.index') }}" class="block py-2 hover:bg-gray-100 dark:hover:bg-slate-700 px-3 rounded-lg text-sm text-gray-700 dark:text-gray-300">Pemeriksaan</a>
                    <a href="{{ route('admin.transactions.index') }}" class="block py-2 hover:bg-gray-100 dark:hover:bg-slate-700 px-3 rounded-lg text-sm text-gray-700 dark:text-gray-300">Transaksi</a>
                    <a href="{{ route('admin.users.index') }}" class="block py-2 hover:bg-gray-100 dark:hover:bg-slate-700 px-3 rounded-lg text-sm text-gray-700 dark:text-gray-300">User</a>
                @endrole
                @role('user')
                    <a href="{{ route('laptops.index') }}" class="block py-2 hover:bg-gray-100 dark:hover:bg-slate-700 px-3 rounded-lg text-sm text-gray-700 dark:text-gray-300">Katalog</a>
                    <a href="{{ route('transactions.index') }}" class="block py-2 hover:bg-gray-100 dark:hover:bg-slate-700 px-3 rounded-lg text-sm text-gray-700 dark:text-gray-300">Riwayat</a>
                @endrole
                <a href="{{ route('profile.edit') }}" class="block py-2 hover:bg-gray-100 dark:hover:bg-slate-700 px-3 rounded-lg text-sm text-gray-700 dark:text-gray-300">Profil Saya</a>
                <form action="/logout" method="POST" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full text-left bg-red-500 text-white py-2 px-3 rounded-lg text-sm font-semibold hover:bg-red-600 transition-smooth">Logout</button>
                </form>
            @else
                <a href="{{ route('laptops.index') }}" class="block py-2 hover:bg-gray-100 dark:hover:bg-slate-700 px-3 rounded-lg text-sm text-gray-700 dark:text-gray-300">Katalog</a>
                <a href="{{ route('login') }}" class="block text-center mt-3 bg-blue-600 text-white py-2 rounded-xl text-sm font-bold shadow-sm hover:bg-blue-700 transition-smooth">Login</a>
            @endauth
            <div class="pt-2 border-t border-gray-200 dark:border-slate-700">
                <button onclick="toggleTheme()" class="w-full flex items-center gap-2 py-2 px-3 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-smooth">
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <span class="dark:hidden">Mode Gelap</span>
                    <span class="hidden dark:inline">Mode Terang</span>
                </button>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-6 px-4 mb-12">
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 p-4 rounded-xl mb-6 text-sm font-medium transition-smooth">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 rounded-xl mb-6 text-sm font-medium transition-smooth">
                {{ $errors->first() }}
            </div>
        @endif
        @yield('content')
    </div>

    <!-- Footer Korporat -->
    <footer class="bg-white dark:bg-slate-800 border-t border-gray-200 dark:border-slate-700 mt-20 py-10 text-center text-xs text-gray-500 dark:text-gray-400 no-print transition-colors duration-300">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <div class="bg-blue-600 p-1.5 rounded-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="font-semibold text-gray-700 dark:text-gray-300">Sewa Laptop Enterprise Core</span>
                </div>
                <p>&copy; {{ date('Y') }} Sistem Manajemen & Penyewaan Laptop Terintegrasi. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
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
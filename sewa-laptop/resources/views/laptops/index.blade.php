@extends('layouts.app')
@section('title', 'Katalog Laptop')
@section('content')
<div class="mb-8">
    <form action="{{ route('laptops.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari merk atau tipe laptop..." class="border border-gray-200 dark:border-slate-600 rounded-xl pl-10 pr-4 py-2.5 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-smooth shadow-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500">
        </div>
        <select name="sort" class="border border-gray-200 dark:border-slate-600 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-smooth shadow-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-gray-100">
            <option value="">Urutkan Harga</option>
            <option value="cheap" {{ request('sort') == 'cheap' ? 'selected' : '' }}>Termurah</option>
            <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>Termahal</option>
        </select>
        <button class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition-smooth shadow-sm hover:shadow-md">Cari</button>
    </form>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($laptops as $laptop)
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition-smooth">
        <div>
            @if($laptop->image)
                <img src="{{ asset('storage/' . $laptop->image) }}" class="w-full h-40 object-cover transition-transform duration-300 hover:scale-[101%]">
            @else
                <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center text-gray-400 dark:text-gray-500">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            @endif
            <div class="p-5">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-1">{{ $laptop->merk }} - {{ $laptop->tipe }}</h3>
                <p class="text-blue-600 dark:text-blue-400 font-bold mb-3 text-xl">Rp {{ number_format($laptop->harga_sewa_per_hari, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500 dark:text-gray-400">/hari</span></p>
                <div class="mt-4 p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-100 dark:border-slate-600 text-center text-xs">
                    <div class="text-gray-600 dark:text-gray-300">Stok Tersedia: <span class="font-bold {{ $laptop->stok > 2 ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400' }}">{{ $laptop->stok }} unit</span></div>
                </div>
            </div>
        </div>
        <div class="p-5 pt-0">
            @if($laptop->stok > 0 && $laptop->status == 'ready')
                <a href="{{ route('laptops.show', $laptop->id) }}" class="block text-center bg-blue-600 text-white font-bold py-2.5 rounded-xl hover:bg-blue-700 transition-smooth shadow-sm hover:shadow-md">Sewa Sekarang</a>
            @else
                <button disabled class="w-full bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-gray-500 font-bold py-2.5 rounded-xl cursor-not-allowed">Stok Habis</button>
            @endif
        </div>
    </div>
    @endforeach
</div>
<div class="mt-8">{{ $laptops->links() }}</div>
@endsection

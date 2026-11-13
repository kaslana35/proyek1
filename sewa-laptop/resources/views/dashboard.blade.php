@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-sm text-gray-500">Ringkasan operasional dan statistik penyewaan laptop.</p>
    </div>
    <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-xl border border-blue-100 text-sm font-semibold">
        🟢 Sistem Aktif
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Card Pending -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-smooth">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Transaksi Pending</p>
            <h3 class="text-3xl font-extrabold text-gray-800">{{ $stats['pending_trx'] }}</h3>
            <p class="text-xs text-yellow-600 font-medium mt-1">Perlu persetujuan admin</p>
        </div>
        <div class="bg-yellow-50 text-yellow-600 p-4 rounded-2xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <!-- Card Maintenance -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-smooth">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Unit Maintenance</p>
            <h3 class="text-3xl font-extrabold text-gray-800">{{ $stats['maintenance'] }}</h3>
            <p class="text-xs text-orange-600 font-medium mt-1">Dalam perbaikan / pengecekan</p>
        </div>
        <div class="bg-orange-50 text-orange-600 p-4 rounded-2xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </div>
    </div>

    <!-- Card Pendapatan -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-smooth">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pendapatan</p>
            <h3 class="text-3xl font-extrabold text-gray-800">Rp {{ number_format($stats['pendapatan']) }}</h3>
            <p class="text-xs text-green-600 font-medium mt-1">Dari transaksi sukses</p>
        </div>
        <div class="bg-green-50 text-green-600 p-4 rounded-2xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Statistik Pendapatan -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="font-bold text-gray-800 mb-4 text-lg border-b pb-3">Statistik Pendapatan Bulanan</h2>
        <div class="space-y-4">
            @forelse($pendapatanPerBulan as $data)
                <div class="flex items-center gap-4">
                    <div class="w-24 text-sm font-semibold text-gray-600">{{ $data->bulan }}</div>
                    <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-600 rounded-full" style="width: {{ min(100, ($data->total / 10000000) * 100) }}%"></div>
                    </div>
                    <div class="w-32 text-sm font-bold text-gray-800 text-right text-[10px]">Rp {{ number_format($data->total) }}</div>
                </div>
            @empty
                <p class="text-gray-400 text-sm text-center py-4">Belum ada pendapatan.</p>
            @endforelse
        </div>
    </div>

    <!-- Low Stock Alert & Inspections -->
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="font-bold text-red-600 mb-4 text-lg border-b pb-3 flex items-center gap-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
    Stok Menipis
</h2>
            <div class="space-y-2 text-sm">
                @forelse($lowStockLaptops as $laptop)
                    <div class="flex justify-between text-gray-700">
                        <span>{{ $laptop->merk }} {{ $laptop->tipe }}</span>
                        <span class="font-bold text-red-600">{{ $laptop->stok }} tersisa</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-xs">Stok aman semua.</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="font-bold text-gray-800 mb-4 text-lg border-b pb-3">Inspeksi Terakhir</h2>
            <div class="space-y-2 text-sm text-gray-600">
                @forelse($recentInspections as $ins)
                    <div class="flex justify-between">
                        <span>{{ $ins->laptop->merk }} ({{ $ins->status_sesudah }})</span>
                        <span class="text-xs">{{ $ins->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-xs">Belum ada data inspeksi.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
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
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Transaksi Pending</p>
            <h3 class="text-3xl font-extrabold text-gray-800">{{ $stats['pending_trx'] }}</h3>
            <p class="text-xs text-yellow-600 font-medium mt-1">Perlu persetujuan admin</p>
        </div>
        <div class="bg-yellow-50 text-yellow-600 p-4 rounded-2xl text-2xl">⏳</div>
    </div>

    <!-- Card Maintenance -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Unit Maintenance</p>
            <h3 class="text-3xl font-extrabold text-gray-800">{{ $stats['maintenance'] }}</h3>
            <p class="text-xs text-orange-600 font-medium mt-1">Dalam perbaikan / pengecekan</p>
        </div>
        <div class="bg-orange-50 text-orange-600 p-4 rounded-2xl text-2xl">🛠️</div>
    </div>

    <!-- Card Pendapatan -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pendapatan</p>
            <h3 class="text-3xl font-extrabold text-gray-800">Rp {{ number_format($stats['pendapatan']) }}</h3>
            <p class="text-xs text-green-600 font-medium mt-1">Dari transaksi sukses</p>
        </div>
        <div class="bg-green-50 text-green-600 p-4 rounded-2xl text-2xl">💰</div>
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
            <h2 class="font-bold text-red-600 mb-4 text-lg border-b pb-3 flex items-center gap-2">⚠️ Stok Menipis</h2>
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
@extends('layouts.app')
@section('title', 'Manajemen Stok')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Manajemen Stok</h1>
    <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-smooth">Kembali</a>
</div>

@if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 p-4 rounded-xl mb-6 text-sm font-medium">
        {{ session('success') }}
    </div>
@endif

<!-- Ringkasan Stok -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 text-center">
        <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Total Ready</p>
        <h3 class="text-2xl font-extrabold text-green-600 dark:text-green-400">{{ $summary['ready'] }}</h3>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 text-center">
        <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Total Maintenance</p>
        <h3 class="text-2xl font-extrabold text-orange-600 dark:text-orange-400">{{ $summary['maintenance'] }}</h3>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 text-center">
        <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Total Not Ready</p>
        <h3 class="text-2xl font-extrabold text-red-600 dark:text-red-400">{{ $summary['not_ready'] }}</h3>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-x-auto">
    <table class="w-full text-left min-w-[600px]">
        <thead class="bg-gray-800 dark:bg-slate-700 text-white">
            <tr>
                <th class="p-3">Merek & Tipe</th>
                <th class="p-3">Stok Ready</th>
                <th class="p-3">Stok Maintenance</th>
                <th class="p-3">Stok Not Ready</th>
                <th class="p-3">Transfer (Dari → Ke)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
            @foreach($laptops as $l)
            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-smooth">
                <td class="p-3 font-semibold text-gray-800 dark:text-gray-100">{{ $l->merk }} {{ $l->tipe }}</td>
                <td class="p-3 text-gray-700 dark:text-gray-300">{{ $l->stok }}</td>
                <td class="p-3 text-gray-700 dark:text-gray-300">{{ $l->maintenance_stok }}</td>
                <td class="p-3 text-gray-700 dark:text-gray-300">{{ $l->not_ready_stok }}</td>
                <td class="p-3">
                    <form action="{{ route('admin.stok.transfer', $l->id) }}" method="POST" class="flex flex-wrap gap-2 items-center">
                        @csrf @method('PUT')
                        <select name="from" class="border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300" required>
                            <option value="ready">Ready</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="not_ready">Not Ready</option>
                        </select>
                        <span class="text-gray-400 dark:text-gray-500 text-xs font-bold">→</span>
                        <select name="target" class="border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300" required>
                            <option value="ready">Ready</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="not_ready">Not Ready</option>
                        </select>
                        <input type="number" name="jumlah" min="1" class="w-14 border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300" required placeholder="Qty">
                        <input type="text" name="catatan" class="w-28 border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300" required placeholder="Catatan">
                        <button class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 transition-smooth">Transfer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $laptops->links() }}</div>

<!-- Riwayat Transfer Terakhir -->
<div class="mt-8 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
    <h2 class="font-bold text-gray-800 dark:text-gray-100 mb-4 text-lg border-b border-gray-100 dark:border-slate-700 pb-3">Riwayat Transfer Stok Terakhir</h2>
    <div class="space-y-3 text-sm">
        @forelse(\App\Models\UnitInspection::with('laptop')->latest()->take(10)->get() as $ins)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-slate-700/50 last:border-0">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ $ins->laptop->merk }} {{ $ins->laptop->tipe }}</span>
                    <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300">{{ $ins->status_sebelum }}</span>
                    <span class="text-gray-400 dark:text-gray-500 text-xs font-bold">→</span>
                    <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">{{ $ins->status_sesudah }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ $ins->jumlah }} unit)</span>
                </div>
                <div class="text-xs text-gray-400 dark:text-gray-500">
                    <span class="italic">{{ $ins->catatan }}</span>
                    <span class="ml-2">{{ $ins->created_at->diffForHumans() }}</span>
                </div>
            </div>
        @empty
            <p class="text-gray-400 dark:text-gray-500 text-xs text-center py-4">Belum ada riwayat transfer.</p>
        @endforelse
    </div>
</div>
@endsection
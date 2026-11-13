@extends('layouts.app')
@section('title', 'Manajemen Stok')
@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Manajemen Stok</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola stok fisik dan transfer antar status.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-smooth">Kembali</a>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left min-w-[600px]">
            <thead class="bg-gray-50 dark:bg-slate-700/50 text-gray-600 dark:text-gray-300 uppercase text-xs tracking-wider border-b border-gray-100 dark:border-slate-700">
                <tr>
                    <th class="p-4 font-semibold">Merek & Tipe</th>
                    <th class="p-4 font-semibold">Stok</th>
                    <th class="p-4 font-semibold">Transfer</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($laptops as $l)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-smooth">
                    <td class="p-4 font-bold text-gray-800 dark:text-gray-100">{{ $l->merk }} {{ $l->tipe }}</td>
                    <td class="p-4 text-gray-700 dark:text-gray-300 text-sm">
                        R:{{ $l->stok }} | M:{{ $l->maintenance_stok }} | NR:{{ $l->not_ready_stok }}
                    </td>
                    <td class="p-4">
                        <form action="{{ route('admin.stok.transfer', $l->id) }}" method="POST" class="flex flex-wrap gap-2 items-center">
                            @csrf @method('PUT')
                            <input type="number" name="jumlah" class="w-12 border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300" placeholder="Jml" required>
                            <select name="from" class="border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300">
                                <option value="ready">Ready</option>
                                <option value="maintenance">Maint</option>
                                <option value="not_ready">Not Ready</option>
                            </select>
                            <span class="text-gray-400 dark:text-gray-500 text-xs font-bold">→</span>
                            <select name="target" class="border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300">
                                <option value="maintenance">Maint</option>
                                <option value="not_ready">Not Ready</option>
                                <option value="ready">Ready</option>
                            </select>
                            <input type="text" name="catatan" class="w-24 border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300" placeholder="Catatan" required>
                            <button class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 transition-smooth">Kirim</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $laptops->links() }}</div>
</div>
@endsection
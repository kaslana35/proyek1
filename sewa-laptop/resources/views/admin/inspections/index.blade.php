@extends('layouts.app')
@section('title', 'Riwayat Pemeriksaan Unit')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Riwayat Pemeriksaan Unit</h1>
    <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-smooth">Kembali</a>
</div>

<div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-x-auto">
    <table class="w-full text-left min-w-[600px]">
        <thead class="bg-gray-800 dark:bg-slate-700 text-white">
            <tr>
                <th class="p-3">Laptop</th>
                <th class="p-3">Status Sebelum</th>
                <th class="p-3">Status Sesudah</th>
                <th class="p-3">Catatan</th>
                <th class="p-3">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
            @foreach($inspections as $ins)
            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-smooth">
                <td class="p-3 font-semibold text-gray-800 dark:text-gray-100">{{ $ins->laptop->merk }} {{ $ins->laptop->tipe }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs uppercase font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-slate-700">
                        {{ $ins->status_sebelum }}
                    </span>
                </td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs uppercase font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-slate-700">
                        {{ $ins->status_sesudah }}
                    </span>
                </td>
                <td class="p-3 text-gray-600 dark:text-gray-300">{{ $ins->catatan }}</td>
                <td class="p-3 text-xs text-gray-500 dark:text-gray-400">{{ $ins->created_at->format('d M Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $inspections->links() }}</div>
@endsection
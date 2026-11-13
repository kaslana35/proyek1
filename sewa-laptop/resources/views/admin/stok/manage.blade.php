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

<div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-x-auto">
    <table class="w-full text-left min-w-[600px]">
        <thead class="bg-gray-800 dark:bg-slate-700 text-white">
            <tr>
                <th class="p-3">Merek & Tipe</th>
                <th class="p-3">Stok Ready</th>
                <th class="p-3">Stok Maintenance</th>
                <th class="p-3">Stok Not Ready</th>
                <th class="p-3">Aksi</th>
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
                    <form action="{{ route('admin.stok.transfer', $l->id) }}" method="POST" class="flex gap-2">
                        @csrf @method('PUT')
                        <select name="from" class="border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300">
                            <option value="ready">Ready</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="not_ready">Not Ready</option>
                        </select>
                        <select name="to" class="border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300">
                            <option value="ready">Ready</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="not_ready">Not Ready</option>
                        </select>
                        <input type="number" name="qty" min="1" class="w-16 border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300" required>
                        <button class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 transition-smooth">Transfer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
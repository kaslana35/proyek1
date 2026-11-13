@extends('layouts.app')
@section('title', 'Manajemen Stok')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen Stok</h1>
    <a href="{{ route('admin.laptops.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Tambah</a>
</div>

<div class="grid grid-cols-3 gap-4 mb-6 text-center">
    <div class="bg-green-100 p-4 rounded">Ready: {{ $summary['ready'] }}</div>
    <div class="bg-yellow-100 p-4 rounded">Maint: {{ $summary['maintenance'] }}</div>
    <div class="bg-orange-100 p-4 rounded">Not Ready: {{ $summary['not_ready'] }}</div>
</div>

<table class="w-full bg-white shadow rounded overflow-hidden">
    <thead class="bg-gray-800 text-white text-xs">
        <tr>
            <th class="p-3">Laptop</th>
            <th class="p-3">Stok Ready</th>
            <th class="p-3">Transfer Stok</th>
            <th class="p-3">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($laptops as $l)
        <tr>
            <td class="p-3 font-bold">{{ $l->merk }} {{ $l->tipe }}</td>
            <td class="p-3">R:{{ $l->stok }} | M:{{ $l->maintenance_stok }} | NR:{{ $l->not_ready_stok }}</td>
            <td class="p-3">
                <!-- Ke Maintenance / Not Ready -->
                <form action="{{ route('admin.stok.transfer', $l->id) }}" method="POST" class="flex gap-2 mb-2">
                    @csrf @method('PUT')
                    <input type="number" name="jumlah" class="w-12 border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300" placeholder="Jml" required>
                    <select name="target" class="border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300">
                        <option value="maintenance">Ke Maint</option>
                        <option value="not_ready">Ke Not Ready</option>
                    </select>
                    <button class="bg-red-500 text-white px-2 py-1 text-xs">Kirim</button>
                </form>
                <!-- Ke Ready (dari Maintenance / Not Ready) -->
                <form action="{{ route('admin.stok.transfer', $l->id) }}" method="POST" class="flex gap-2">
                    @csrf @method('PUT')
                    <input type="number" name="jumlah" class="w-12 border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300" placeholder="Jml" required>
                    <input type="hidden" name="target" value="ready">
                    <select name="from" class="border border-gray-200 dark:border-slate-600 rounded px-2 py-1 text-xs bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-300">
                        <option value="maintenance">Dari Maint</option>
                        <option value="not_ready">Dari Not Ready</option>
                    </select>
                    <button class="bg-green-500 text-white px-2 py-1 text-xs">Ambil</button>
                </form>
            </td>
            <td class="p-3">
                <a href="{{ route('admin.laptops.edit', $l->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-xs hover:bg-yellow-600">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

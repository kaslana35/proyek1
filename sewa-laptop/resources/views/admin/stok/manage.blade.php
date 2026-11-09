@extends('layouts.app')
@section('title', 'Manajemen Stok')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen Stok</h1>
</div>

<div class="grid grid-cols-3 gap-4 mb-6 text-center">
    <div class="bg-green-100 p-4 rounded shadow-sm">Ready: {{ $summary['ready'] }}</div>
    <div class="bg-yellow-100 p-4 rounded shadow-sm">Maint: {{ $summary['maintenance'] }}</div>
    <div class="bg-orange-100 p-4 rounded shadow-sm">Not Ready: {{ $summary['not_ready'] }}</div>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-800 text-white text-xs uppercase">
            <tr>
                <th class="p-3">Laptop</th>
                <th class="p-3">Stok Saat Ini</th>
                <th class="p-3">Transfer Stok Universal</th>
            </tr>
        </thead>
        <tbody class="divide-y text-sm">
            @foreach($laptops as $l)
            <tr>
                <td class="p-3 font-bold">{{ $l->merk }} {{ $l->tipe }}</td>
                <td class="p-3">
                    <span class="text-green-600 font-semibold">Ready: {{ $l->stok }}</span> | 
                    <span class="text-yellow-600 font-semibold">Maint: {{ $l->maintenance_stok }}</span> | 
                    <span class="text-orange-600 font-semibold">NR: {{ $l->not_ready_stok }}</span>
                </td>
                <td class="p-3">
                    <form action="{{ route('admin.stok.transfer', $l->id) }}" method="POST" class="flex flex-wrap gap-2 items-center">
                        @csrf @method('PUT')
                        <input type="number" name="jumlah" class="w-16 border rounded p-1 text-xs" placeholder="Jml" min="1" required>
                        
                        <span class="text-xs text-gray-500 font-bold">Dari:</span>
                        <select name="from" class="border rounded p-1 text-xs" required>
                            <option value="ready">Ready</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="not_ready">Not Ready</option>
                        </select>

                        <span class="text-xs text-gray-500 font-bold">Ke:</span>
                        <select name="target" class="border rounded p-1 text-xs" required>
                            <option value="ready">Ready</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="not_ready">Not Ready</option>
                        </select>

                        <input type="text" name="catatan" class="w-full border rounded p-1 text-xs" placeholder="Catatan kerusakan..." required>
                        <button class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 font-semibold w-full">Transfer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">{{ $laptops->links() }}</div>
</div>
@endsection

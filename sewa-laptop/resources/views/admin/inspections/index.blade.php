@extends('layouts.app')
@section('title', 'Riwayat Pemeriksaan Unit')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Riwayat Pemeriksaan Unit</h1>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="w-full text-left min-w-[600px]">
        <thead class="bg-gray-800 text-white text-xs uppercase">
            <tr>
                <th class="p-3">Waktu</th>
                <th class="p-3">Laptop</th>
                <th class="p-3">Jumlah</th>
                <th class="p-3">Status Sebelum</th>
                <th class="p-3">Status Sesudah</th>
                <th class="p-3">Catatan</th>
            </tr>
        </thead>
        <tbody class="divide-y text-sm">
            @forelse($inspections as $inspection)
            <tr class="hover:bg-gray-50">
                <td class="p-3 text-gray-500 text-xs">{{ $inspection->created_at->format('d M Y H:i') }}</td>
                <td class="p-3 font-bold">{{ $inspection->laptop->merk ?? '-' }} {{ $inspection->laptop->tipe ?? '' }}</td>
                <td class="p-3 font-semibold">{{ $inspection->jumlah }} unit</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs uppercase font-bold text-gray-700 bg-gray-100">
                        {{ $inspection->status_sebelum }}
                    </span>
                </td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs uppercase font-bold text-blue-700 bg-blue-100">
                        {{ $inspection->status_sesudah }}
                    </span>
                </td>
                <td class="p-3 text-gray-600 text-xs">{{ $inspection->catatan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-4 text-center text-gray-500">Belum ada riwayat pemeriksaan unit.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $inspections->links() }}</div>
</div>
@endsection

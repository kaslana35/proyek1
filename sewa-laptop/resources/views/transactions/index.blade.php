@extends('layouts.app')
@section('title', 'Riwayat Sewa')
@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Penyewaan Laptop</h1>

    @if($transactions->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
            <p>Belum ada riwayat penyewaan laptop.</p>
            <a href="{{ route('laptops.index') }}" class="inline-block mt-4 bg-blue-600 text-white font-bold px-6 py-2 rounded-xl hover:bg-blue-700 transition">Cari Laptop Sekarang</a>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="p-4">Laptop</th>
                        <th class="p-4">Tanggal Mulai</th>
                        <th class="p-4">Tanggal Selesai</th>
                        <th class="p-4">Total Biaya</th>
                        <th class="p-4">Status / Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @foreach($transactions as $trx)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 font-semibold text-gray-800">
                            {{ $trx->laptop->merk ?? 'Laptop Dihapus' }} - {{ $trx->laptop->tipe ?? '' }}
                        </td>
                        <td class="p-4 text-gray-600">{{ $trx->tanggal_mulai }}</td>
                        <td class="p-4 text-gray-600">{{ $trx->tanggal_selesai }}</td>
                        <td class="p-4 text-green-600 font-bold">Rp {{ number_format($trx->total_biaya, 2) }}</td>
                        <td class="p-4">
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2.5 py-1 rounded-full font-semibold">
                                Menunggu Approval
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

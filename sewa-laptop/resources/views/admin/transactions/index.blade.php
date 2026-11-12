@extends('layouts.app')
@section('title', 'Admin Transaksi')
@section('content')
<div class="flex justify-between items-center mb-6 no-print">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen Transaksi</h1>
    <button onclick="window.print()" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition text-sm font-bold">
        🖨️ Cetak Laporan
    </button>
</div>
<h1 class="text-2xl font-bold text-gray-800 mb-6 print-only hidden">Laporan Transaksi Penyewaan Laptop</h1>

<!-- Logo saat cetak -->
<div class="print-only hidden mb-4 flex items-center gap-2">
    <div class="bg-blue-600 p-1.5 rounded-lg inline-block">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
    </div>
    <span class="font-bold text-lg text-gray-800">Sewa Laptop</span>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        nav { display: none !important; }
        .print-only { 
            display: block !important; 
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact; 
        }
        body { background: white !important; }
    }
</style>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ $errors->first() }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="w-full text-left min-w-[600px]">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="p-3">User</th>
                <th class="p-3">Laptop</th>
                <th class="p-3">Periode Sewa</th>
                <th class="p-3">Total Biaya</th>
                <th class="p-3">Status</th>
                <th class="p-3 no-print">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($transactions as $trx)
            <tr>
                <td class="p-3">{{ $trx->user?->name ?? 'User Dihapus' }}</td>
                <td class="p-3">{{ $trx->laptop?->merk ?? 'Laptop Dihapus' }} {{ $trx->laptop?->tipe ?? '' }}</td>
                <td class="p-3 text-xs text-gray-600">{{ $trx->tanggal_mulai }} s/d {{ $trx->tanggal_selesai }}</td>
                <td class="p-3 font-semibold text-gray-800">Rp {{ number_format($trx->total_biaya, 0, ',', '.') }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 text-xs rounded-full font-semibold 
                        {{ $trx->status == 'approved' ? 'bg-green-100 text-green-800' : 
                          ($trx->status == 'returned' ? 'bg-blue-100 text-blue-800' : 
                          ($trx->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                        {{ ucfirst($trx->status) }}
                    </span>
                </td>
                <td class="p-3 no-print flex gap-2">
                    @if($trx->status == 'pending')
                        <form action="{{ route('admin.transactions.approve', $trx->id) }}" method="POST">
                            @csrf @method('PUT')
                            <button class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600 transition">Setujui</button>
                        </form>
                    @endif
                    @if($trx->status == 'approved')
                        <form action="{{ route('admin.transactions.complete', $trx->id) }}" method="POST" onsubmit="return confirm('Selesaikan penyewaan dan kembalikan stok?')">
                            @csrf @method('PUT')
                            <button class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition">Selesai</button>
                        </form>
                    @endif
                    @if(!in_array($trx->status, ['cancelled', 'returned']))
                        <form action="{{ route('admin.transactions.cancel', $trx->id) }}" method="POST" onsubmit="return confirm('Batalkan transaksi dan kembalikan stok?')">
                            @csrf @method('PUT')
                            <button class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 transition">Batalkan</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4 bg-gray-50 p-4 rounded-lg text-right font-bold text-gray-800">
    Total Pendapatan Transaksi di Halaman Ini: Rp {{ number_format($transactions->sum('total_biaya'), 0, ',', '.') }}
</div>
<div class="mt-6">{{ $transactions->links() }}</div>
@endsection

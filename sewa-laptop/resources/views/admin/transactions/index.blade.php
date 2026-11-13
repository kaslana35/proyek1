@extends('layouts.app')
@section('title', 'Admin Transaksi')
@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 no-print">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Manajemen Transaksi</h1>
    <button onclick="window.print()" class="bg-gray-700 dark:bg-slate-600 text-white px-4 py-2 rounded-lg hover:bg-gray-800 dark:hover:bg-slate-500 transition-smooth text-sm font-bold shadow-sm whitespace-nowrap">
        Cetak Laporan
    </button>
</div>
<h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 print-only hidden">Laporan Transaksi Penyewaan Laptop</h1>

<div class="print-only hidden mb-4 flex items-center gap-2">
    <div class="bg-blue-600 p-1.5 rounded-lg inline-block">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
    </div>
    <span class="font-bold text-lg text-gray-800 dark:text-gray-100">Sewa Laptop</span>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        nav { display: none !important; }
        .print-only { display: block !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        body { background: white !important; }
    }
</style>

@if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 p-4 rounded-xl mb-6 text-sm font-medium">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 p-4 rounded-xl mb-6 text-sm font-medium">
        {{ $errors->first() }}
    </div>
@endif

<div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-x-auto">
    <table class="w-full text-left min-w-[640px]">
        <thead class="bg-gray-800 dark:bg-slate-700 text-white">
            <tr>
                <th class="p-3 text-sm">User</th>
                <th class="p-3 text-sm">Laptop</th>
                <th class="p-3 text-sm">Periode</th>
                <th class="p-3 text-sm">Biaya</th>
                <th class="p-3 text-sm">Status</th>
                <th class="p-3 text-sm no-print">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
            @foreach($transactions as $trx)
            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-smooth">
                <td class="p-3 text-sm text-gray-700 dark:text-gray-300">{{ $trx->user?->name ?? 'User Dihapus' }}</td>
                <td class="p-3 text-sm text-gray-700 dark:text-gray-300">{{ $trx->laptop?->merk ?? 'Laptop Dihapus' }} {{ $trx->laptop?->tipe ?? '' }}</td>
                <td class="p-3 text-xs text-gray-600 dark:text-gray-400">{{ $trx->tanggal_mulai }} s/d {{ $trx->tanggal_selesai }}</td>
                <td class="p-3 text-sm font-semibold text-gray-800 dark:text-gray-100">Rp {{ number_format($trx->total_biaya, 0, ',', '.') }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 text-xs rounded-full font-semibold 
                        {{ $trx->status == 'approved' ? 'bg-green-50 dark:bg-green-900/20 text-green-800 dark:text-green-400' : 
                          ($trx->status == 'returned' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-400' : 
                          ($trx->status == 'cancelled' ? 'bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-400' : 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-400')) }}">
                        {{ ucfirst($trx->status) }}
                    </span>
                </td>
                <td class="p-3 no-print flex flex-wrap gap-1">
                    @if($trx->status == 'pending')
                        <form action="{{ route('admin.transactions.approve', $trx->id) }}" method="POST">
                            @csrf @method('PUT')
                            <button class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600 transition-smooth">Setujui</button>
                        </form>
                    @endif
                    @if($trx->status == 'approved')
                        <form action="{{ route('admin.transactions.complete', $trx->id) }}" method="POST" onsubmit="return confirm('Selesaikan penyewaan dan kembalikan stok?')">
                            @csrf @method('PUT')
                            <button class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition-smooth">Selesai</button>
                        </form>
                    @endif
                    @if(!in_array($trx->status, ['cancelled', 'returned']))
                        <form action="{{ route('admin.transactions.cancel', $trx->id) }}" method="POST" onsubmit="return confirm('Batalkan transaksi dan kembalikan stok?')">
                            @csrf @method('PUT')
                            <button class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 transition-smooth">Batalkan</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4 bg-gray-50 dark:bg-slate-700/50 p-4 rounded-lg text-right font-bold text-gray-800 dark:text-gray-100 text-sm">
    Total Pendapatan di Halaman Ini: Rp {{ number_format($transactions->sum('total_biaya'), 0, ',', '.') }}
</div>
<div class="mt-6">{{ $transactions->links() }}</div>
@endsection
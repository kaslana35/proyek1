@extends('layouts.app')
@section('title', 'Riwayat & Cetak Bukti Sewa')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Riwayat & Cetak Bukti Sewa</h1>
    <form action="{{ route('transactions.index') }}" method="GET" class="flex items-center gap-2">
        <select name="status" onchange="this.form.submit()" class="border border-gray-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-800 font-medium text-gray-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </form>
</div>

@if($transactions->isEmpty())
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-8 text-center text-gray-500 dark:text-gray-400">
        <p>Belum ada riwayat penyewaan laptop.</p>
        <a href="{{ route('laptops.index') }}" class="inline-block mt-4 bg-blue-600 text-white font-bold px-6 py-2.5 rounded-xl hover:bg-blue-700 transition-smooth text-sm shadow-sm">Cari Laptop Sekarang</a>
    </div>
@else
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[600px]">
            <thead class="bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-gray-300 uppercase text-xs tracking-wider border-b border-gray-100 dark:border-slate-700">
                <tr>
                    <th class="p-4 font-semibold">Laptop</th>
                    <th class="p-4 font-semibold">Mulai</th>
                    <th class="p-4 font-semibold">Selesai</th>
                    <th class="p-4 font-semibold">Total Biaya</th>
                    <th class="p-4 font-semibold no-print">Status & Ulasan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-sm">
                @foreach($transactions as $trx)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-smooth">
                    <td class="p-4 font-bold text-gray-800 dark:text-gray-100">
                        {{ $trx->laptop->merk ?? 'Laptop Dihapus' }} {{ $trx->laptop->tipe ?? '' }}
                    </td>
                    <td class="p-4 text-gray-600 dark:text-gray-300">{{ $trx->tanggal_mulai }}</td>
                    <td class="p-4 text-gray-600 dark:text-gray-300">{{ $trx->tanggal_selesai }}</td>
                    <td class="p-4 text-gray-800 dark:text-gray-100 font-bold">Rp {{ number_format($trx->total_biaya, 0, ',', '.') }}</td>
                    <td class="p-4 no-print">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800',
                                'approved' => 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800',
                                'active' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800',
                                'returned' => 'bg-gray-50 dark:bg-slate-700/50 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-slate-600',
                                'cancelled' => 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800'
                            ];
                            $status = $trx->status ?? 'pending';
                        @endphp
                        <div class="flex flex-col gap-1.5 items-start">
                            <span class="{{ $statusColors[$status] ?? 'bg-gray-50 dark:bg-slate-700/50' }} text-xs px-3 py-1 rounded-full font-semibold border uppercase">
                                {{ $status }}
                            </span>
                            @if($status == 'approved' && \Carbon\Carbon::parse($trx->tanggal_selesai)->isPast())
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-500 text-white font-bold animate-pulse">Overdue</span>
                            @elseif($status == 'approved' && \Carbon\Carbon::parse($trx->tanggal_selesai)->isToday())
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-orange-500 text-white font-bold">Terakhir</span>
                            @endif
                            @if(in_array($status, ['approved', 'active', 'returned']))
                                <button onclick="document.getElementById('review-box-{{ $trx->id }}').classList.toggle('hidden')" class="text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">
                                    Beri / Lihat Ulasan
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @if(in_array($status, ['approved', 'active', 'returned']))
                <tr id="review-box-{{ $trx->id }}" class="hidden bg-gray-50 dark:bg-slate-700/30 no-print">
                    <td colspan="5" class="p-4 border-t border-gray-100 dark:border-slate-700">
                        <form action="{{ route('reviews.store') }}" method="POST" class="flex items-center gap-3">
                            @csrf
                            <input type="hidden" name="laptop_id" value="{{ $trx->laptop_id }}">
                            <select name="rating" class="border border-gray-200 dark:border-slate-600 rounded-xl px-3 py-1.5 text-xs bg-white dark:bg-slate-800 font-medium text-gray-700 dark:text-gray-300" required>
                                <option value="5">5 - Sangat Puas</option>
                                <option value="4">4 - Puas</option>
                                <option value="3">3 - Cukup</option>
                                <option value="2">2 - Kurang</option>
                                <option value="1">1 - Buruk</option>
                            </select>
                            <input type="text" name="komentar" placeholder="Tuliskan ulasan atau testimoni Anda..." class="flex-1 border border-gray-200 dark:border-slate-600 rounded-xl px-4 py-1.5 text-xs bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300" required>
                            <button type="submit" class="bg-blue-600 text-white font-semibold px-4 py-1.5 rounded-xl text-xs hover:bg-blue-700 transition-smooth">Kirim Ulasan</button>
                        </form>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
@endif

<div class="mt-6">{{ $transactions->links() }}</div>

<div class="hidden print-only mt-12 flex justify-between text-xs text-black">
    <div class="text-center">
        <p>Mengetahui,</p>
        <p class="font-bold mt-12">Admin SewaLaptop</p>
    </div>
    <div class="text-center">
        <p>Peminjam,</p>
        <p class="font-bold mt-12">{{ auth()->user()->name }}</p>
    </div>
</div>
@endsection

<style>
    @media print {
        .no-print { display: none !important; }
        nav { display: none !important; }
        .print-only { display: block !important; }
        body { background: white !important; }
    }
</style>

@extends('layouts.app')
@section('title', 'Riwayat Sewa')
@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="flex justify-between items-center mb-6 no-print">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat & Cetak Bukti Sewa</h1>
            <p class="text-sm text-gray-500">Daftar transaksi penyewaan atas nama Anda.</p>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('transactions.index') }}" method="GET" class="flex gap-2">
                <select name="status" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-3 py-2 text-sm bg-white font-medium text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>
            @if($transactions->isNotEmpty())
                <button onclick="window.print()" class="bg-gray-800 text-white font-semibold px-4 py-2 rounded-xl text-sm hover:bg-gray-700 transition shadow-sm flex items-center gap-2">
                    🖨️ Cetak Bukti
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl mb-6 text-sm font-medium no-print">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header Khusus Print -->
    <div class="hidden print-only mb-6 text-center">
        <h1 class="text-2xl font-bold text-black">BUKTI PENYEWAAN LAPTOP</h1>
        <p class="text-sm text-black">SewaLaptop Enterprise Core</p>
        <p class="text-xs text-black mt-1">Nama Peminjam: {{ auth()->user()->name }} ({{ auth()->user()->email }})</p>
        <hr class="my-4 border-black">
    </div>

    @if($transactions->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
            <p>Belum ada riwayat penyewaan laptop.</p>
            <a href="{{ route('laptops.index') }}" class="inline-block mt-4 bg-blue-600 text-white font-bold px-6 py-2.5 rounded-xl hover:bg-blue-700 transition text-sm">Cari Laptop Sekarang</a>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider border-b">
                    <tr>
                        <th class="p-4 font-semibold">Laptop</th>
                        <th class="p-4 font-semibold">Mulai</th>
                        <th class="p-4 font-semibold">Selesai</th>
                        <th class="p-4 font-semibold">Total Biaya</th>
                        <th class="p-4 font-semibold no-print">Status & Ulasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($transactions as $trx)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-4 font-bold text-gray-800">
                            {{ $trx->laptop->merk ?? 'Laptop Dihapus' }} {{ $trx->laptop->tipe ?? '' }}
                        </td>
                        <td class="p-4 text-gray-600">{{ $trx->tanggal_mulai }}</td>
                        <td class="p-4 text-gray-600">{{ $trx->tanggal_selesai }}</td>
                        <td class="p-4 text-gray-800 font-bold">Rp {{ number_format($trx->total_biaya, 0, ',', '.') }}</td>
                        <td class="p-4 no-print">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'approved' => 'bg-green-50 text-green-700 border-green-200',
                                    'active' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'returned' => 'bg-gray-50 text-gray-700 border-gray-200',
                                    'cancelled' => 'bg-red-50 text-red-700 border-red-200'
                                ];
                                $status = $trx->status ?? 'pending';
                            @endphp
                            <div class="flex flex-col gap-1.5 items-start">
                                <span class="{{ $statusColors[$status] ?? 'bg-gray-50' }} text-xs px-3 py-1 rounded-full font-semibold border uppercase">
                                    {{ $status }}
                                </span>
                                @if($status == 'approved' && \Carbon\Carbon::parse($trx->tanggal_selesai)->isPast())
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-500 text-white font-bold animate-pulse">⚠️ Overdue</span>
                                @elseif($status == 'approved' && \Carbon\Carbon::parse($trx->tanggal_selesai)->isToday())
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-orange-500 text-white font-bold">⏰ Terakhir</span>
                                @endif
                                @if(in_array($status, ['approved', 'active', 'returned']))
                                    <button onclick="document.getElementById('review-box-{{ $trx->id }}').classList.toggle('hidden')" class="text-xs text-blue-600 font-semibold hover:underline">
                                        ⭐ Beri / Lihat Ulasan
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @if(in_array($status, ['approved', 'active', 'returned']))
                    <tr id="review-box-{{ $trx->id }}" class="hidden bg-gray-50 no-print">
                        <td colspan="5" class="p-4 border-t">
                            <form action="{{ route('reviews.store') }}" method="POST" class="flex items-center gap-3">
                                @csrf
                                <input type="hidden" name="laptop_id" value="{{ $trx->laptop_id }}">
                                <select name="rating" class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs bg-white font-medium" required>
                                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                    <option value="4">⭐⭐⭐⭐ (4)</option>
                                    <option value="3">⭐⭐⭐ (3)</option>
                                    <option value="2">⭐⭐ (2)</option>
                                    <option value="1">⭐ (1)</option>
                                </select>
                                <input type="text" name="komentar" placeholder="Tuliskan ulasan atau testimoni Anda..." class="flex-1 border border-gray-200 rounded-xl px-4 py-1.5 text-xs bg-white" required>
                                <button type="submit" class="bg-blue-600 text-white font-semibold px-4 py-1.5 rounded-xl text-xs hover:bg-blue-700 transition">Kirim Ulasan</button>
                            </form>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

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
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        nav { display: none !important; }
        .print-only { display: block !important; }
        body { background: white !important; }
    }
</style>
@endsection

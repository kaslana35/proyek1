@extends('layouts.app')
@section('title', $laptop->merk . ' ' . $laptop->tipe)
@section('content')
<div class="max-w-5xl mx-auto py-8">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <!-- Foto -->
            <div class="bg-gray-50 flex items-center justify-center p-8">
                <img src="{{ asset('storage/' . $laptop->image) }}" alt="{{ $laptop->merk }}" class="w-full max-h-96 object-contain rounded-2xl">
            </div>
            
            <!-- Detail -->
            <div class="p-8">
                <div class="mb-4">
                    <span class="bg-blue-50 text-blue-700 font-bold px-3 py-1 rounded-full text-xs uppercase tracking-wider">{{ $laptop->merk }}</span>
                    <h1 class="text-3xl font-extrabold text-gray-900 mt-2">{{ $laptop->tipe }}</h1>
                    <p class="text-2xl font-bold text-blue-600 mt-2">Rp {{ number_format($laptop->harga_sewa_per_hari) }} <span class="text-sm text-gray-500 font-medium">/ hari</span></p>
                </div>

                <div class="border-t border-gray-100 py-6">
                    <h3 class="font-bold text-gray-800 mb-3">Spesifikasi Lengkap</h3>
                    <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">{{ $laptop->spesifikasi }}</p>
                </div>

                <div class="border-t border-gray-100 py-6">
                    <h3 class="font-bold text-gray-800 mb-4">Ulasan Peminjam ({{ $laptop->reviews()->count() }})</h3>
                    <div class="space-y-4">
                        @forelse($laptop->reviews as $review)
                            <div class="bg-gray-50 p-4 rounded-xl">
                                <div class="flex justify-between mb-1">
                                    <span class="text-xs font-bold text-gray-700">{{ $review->user->name }}</span>
                                    <span class="text-xs text-yellow-500 font-bold">
                                        {{ str_repeat('⭐', $review->rating) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $review->komentar }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">Belum ada ulasan untuk laptop ini.</p>
                        @endforelse
                    </div>
                </div>

                <div class="flex items-center gap-4 border-t border-gray-100 pt-6">
                    <div class="flex-1">
                        <p class="text-xs text-gray-400 font-semibold uppercase">Status Stok</p>
                        <p class="font-bold {{ $laptop->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $laptop->stok > 0 ? 'Tersedia (' . $laptop->stok . ' Unit)' : 'Habis' }}
                        </p>
                    </div>
                </div>

                @if($laptop->stok > 0)
                    <form action="{{ route('laptops.sewa', $laptop->id) }}" method="POST" class="mt-8 border-t pt-6">
                        @csrf
                        
                        <!-- Kotak Syarat & Ketentuan -->
                        <div class="mb-4 bg-gray-50 p-4 rounded-2xl border border-gray-200 text-xs text-gray-600">
                            <h4 class="font-bold text-gray-800 mb-2">📋 Syarat & Ketentuan Sewa:</h4>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Penyewa wajib melengkapi data profil (No. Telp & Alamat).</li>
                                <li>Wajib menunjukkan identitas (KTP/KTM) saat pengambilan unit.</li>
                                <li>Bertanggung jawab penuh atas keutuhan fisik dan kelengkapan unit.</li>
                                <li>Keterlambatan pengembalian akan dikenakan denda harian.</li>
                            </ul>
                            <div class="mt-3 flex items-center gap-2 pt-2 border-t border-gray-200">
                                <input type="checkbox" id="terms" name="terms" required class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="terms" class="font-semibold text-gray-700 cursor-pointer">Saya menyetujui syarat & ketentuan sewa di atas.</label>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="w-full border-gray-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="w-full border-gray-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100" required>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition">Sewa Sekarang</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Verifikasi Email')
@section('content')
<div class="max-w-md mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-8 text-center">
    <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100">Verifikasi Email Anda</h2>
    <a href="{{ route('laptops.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 mb-4 transition-smooth">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Katalog
    </a>
    <p class="text-gray-600 dark:text-gray-300 mb-6">Kami telah mengirim link verifikasi ke email Anda. Silakan cek inbox/spam.</p>
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition-smooth">
            Kirim Ulang Link Verifikasi
        </button>
    </form>
    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="text-gray-500 dark:text-gray-400 hover:text-red-500 text-sm">Logout</button>
    </form>
</div>
@endsection

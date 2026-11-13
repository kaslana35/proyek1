@extends('layouts.app')
@section('title', 'Edit Profil')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            Informasi Profil
        </h3>
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full p-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 transition outline-none text-gray-800 dark:text-gray-100" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full p-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 transition outline-none text-gray-800 dark:text-gray-100" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="08xxxxxxxxxx" class="w-full p-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 transition outline-none text-gray-800 dark:text-gray-100">
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Alamat</label>
                <textarea name="address" rows="3" placeholder="Alamat domisili..." class="w-full p-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 transition outline-none text-gray-800 dark:text-gray-100">{{ old('address', auth()->user()->address) }}</textarea>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-smooth shadow-sm">Simpan Perubahan</button>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            Ubah Password
        </h3>
        <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Password Saat Ini</label>
                <input type="password" name="current_password" class="w-full p-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-yellow-500 transition outline-none text-gray-800 dark:text-gray-100" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Password Baru</label>
                <input type="password" name="password" class="w-full p-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-yellow-500 transition outline-none text-gray-800 dark:text-gray-100" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-semibold mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="w-full p-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-yellow-500 transition outline-none text-gray-800 dark:text-gray-100" required>
            </div>
            <button type="submit" class="w-full bg-yellow-500 text-white py-3 rounded-xl font-bold hover:bg-yellow-600 transition-smooth shadow-sm">Update Password</button>
        </form>
    </div>
</div>
@endsection
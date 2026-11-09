@extends('layouts.app')
@section('title', 'Edit Profil')
@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Informasi Profil -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">👤</span> Profil Saya
            </h3>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition outline-none">
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition outline-none">
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Nomor HP / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="08xxxxxxxxxx" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition outline-none">
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Alamat Lengkap</label>
                    <textarea name="address" rows="3" placeholder="Alamat domisili..." class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition outline-none">{{ old('address', auth()->user()->address) }}</textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition transform hover:scale-[1.02]">Update Profil</button>
            </form>
        </div>

        <!-- Ubah Password -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="bg-yellow-100 text-yellow-600 p-2 rounded-lg mr-3">🔒</span> Keamanan
            </h3>
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Password Saat Ini</label>
                    <input type="password" name="current_password" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 transition outline-none">
                    @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Password Baru</label>
                    <input type="password" name="password" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 transition outline-none">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 transition outline-none">
                </div>
                <button type="submit" class="w-full bg-yellow-500 text-white font-bold py-3 rounded-xl hover:bg-yellow-600 transition transform hover:scale-[1.02]">Ganti Password</button>
            </form>
        </div>
    </div>
</div>
@endsection

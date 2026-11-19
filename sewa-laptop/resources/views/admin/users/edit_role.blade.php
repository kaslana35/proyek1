@extends('layouts.app')
@section('title', 'Edit Role')
@section('content')
<div class="max-w-md mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100 border-b border-gray-100 dark:border-slate-700 pb-2">Edit Role: {{ $user->name }}</h2>
    <form action="{{ route('admin.users.update_role', $user->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-6">
            <label for="role" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Pilih Role:</label>
            <select name="role" id="role" class="w-full px-3 py-2 border border-gray-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-yellow-500 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100">
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-smooth">Update</button>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 mb-4 transition-smooth">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    Kembali ke Manajemen User
</a>
        </div>
    </form>
</div>
@endsection

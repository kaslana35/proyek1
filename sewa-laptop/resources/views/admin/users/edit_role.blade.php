@extends('layouts.app')
@section('title', 'Edit Role')
@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">Edit Role: {{ $user->name }}</h2>
    <form action="{{ route('admin.users.update_role', $user->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Role:</label>
            <select name="role" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500">
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">Update</button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Kembali</a>
        </div>
    </form>
</div>
@endsection

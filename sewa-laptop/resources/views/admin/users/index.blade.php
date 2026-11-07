@extends('layouts.app')
@section('title', 'Manajemen User')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen User & Role</h1>
    <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Kembali</a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="p-3">Nama</th>
                <th class="p-3">Email</th>
                <th class="p-3">Role</th>
                <th class="p-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($users as $u)
            <tr class="hover:bg-gray-50">
                <td class="p-3 font-semibold text-gray-700">{{ $u->name }}</td>
                <td class="p-3 text-gray-600">{{ $u->email }}</td>
                <td class="p-3">
                    <span class="bg-blue-100 text-blue-800 text-xs px-2.5 py-1 rounded-full font-semibold">
                        {{ $u->getRoleNames()->implode(', ') }}
                    </span>
                </td>
                <td class="p-3 text-center">
                    <a href="{{ route('admin.users.edit_role', $u->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-xs hover:bg-yellow-600 transition">Edit Role</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Manajemen User')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Manajemen User & Role</h1>
    <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-smooth">Kembali</a>
</div>

<div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-x-auto max-w-6xl">
    <table class="w-full text-left border-collapse min-w-[600px]">
        <thead class="bg-gray-800 dark:bg-slate-700 text-white">
            <tr>
                <th class="p-3 text-sm">Nama</th>
                <th class="p-3 text-sm">Email</th>
                <th class="p-3 text-sm">Role</th>
                <th class="p-3 text-center text-sm">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
            @foreach($users as $u)
            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-smooth">
                <td class="p-3 font-semibold text-gray-800 dark:text-gray-100">{{ $u->name }}</td>
                <td class="p-3 text-gray-600 dark:text-gray-300">{{ $u->email }}</td>
                <td class="p-3">
                    <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 text-xs px-2.5 py-1 rounded-full font-semibold">
                        {{ $u->getRoleNames()->implode(', ') }}
                    </span>
                </td>
                <td class="p-3 text-center">
                    <a href="{{ route('admin.users.edit_role', $u->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-xs hover:bg-yellow-600 transition-smooth">Edit Role</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function editRole(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit_role', compact('user', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);

        // Prevent self-lockout: admin cannot remove their own admin role
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return back()->withErrors(['error' => 'Anda tidak dapat mencabut role admin dari akun Anda sendiri.']);
        }

        // Prevent removing the last admin
        if ($request->role !== 'admin' && $user->hasRole('admin')) {
            $adminCount = User::role('admin')->count();
            if ($adminCount <= 1) {
                return back()->withErrors(['error' => 'Tidak dapat menghapus role admin terakhir. Sistem harus memiliki minimal 1 admin.']);
            }
        }

        $user->syncRoles($request->role);
        return redirect()->route('admin.users.index')->with('success', 'Role user berhasil diupdate!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(25);

        return view('admin.users.index', compact('users'));
    }

    public function toggleBan(User $user)
    {
        $user->update(['is_banned' => !$user->is_banned]);
        $action = $user->is_banned ? 'banned' : 'unbanned';
        return back()->with('success', "User {$user->name} has been {$action}.");
    }

    public function makeAdmin(User $user)
    {
        $user->update(['role' => $user->role === 'admin' ? 'user' : 'admin']);
        $role = $user->role;
        return back()->with('success', "User {$user->name} role changed to {$role}.");
    }
}

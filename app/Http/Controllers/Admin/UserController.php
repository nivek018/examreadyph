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

    public function toggleSubscription(User $user)
    {
        if ($user->isPremium()) {
            // Cancel active subscription
            $user->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);
            return back()->with('success', "Pro subscription for {$user->name} has been revoked.");
        } else {
            // Grant 30 days Pro subscription
            $plan = \App\Models\SubscriptionPlan::where('slug', 'pro-monthly')->first();
            \App\Models\Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan ? $plan->id : 1,
                'starts_at' => now(),
                'expires_at' => now()->addDays(30),
                'status' => 'active',
                'auto_renew' => false,
            ]);
            return back()->with('success', "Granted 30 days Pro subscription to {$user->name}.");
        }
    }
}

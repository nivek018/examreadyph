<x-admin-layout title="Users">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">User Management</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">View, search, ban/unban, and manage user roles.</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="card p-4 mb-6 flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..."
               class="flex-1 px-4 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
        <select name="role" class="px-4 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
        </select>
        <button class="btn-brand px-4 py-2 text-sm"><i class="fa-solid fa-search"></i> Filter</button>
    </form>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase">User</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Role</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Joined</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-sm">{{ $user->initials }}</div>
                            <div>
                                <div class="font-semibold text-slate-900 dark:text-white">{{ $user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($user->role === 'admin')
                            <span class="badge-purple text-[10px]">Admin</span>
                        @else
                            <span class="badge-blue text-[10px]">User</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($user->is_banned)
                            <span class="px-2 py-0.5 rounded bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-400 text-[10px] font-bold border border-rose-200 dark:border-rose-800">Banned</span>
                        @else
                            <span class="badge-emerald text-[10px]">Active</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center text-xs text-slate-500">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <form method="POST" action="{{ route('admin.users.toggleBan', $user) }}">
                                @csrf
                                <button class="text-xs font-medium {{ $user->is_banned ? 'text-emerald-600 hover:text-emerald-500' : 'text-rose-500 hover:text-rose-400' }}">
                                    <i class="fa-solid {{ $user->is_banned ? 'fa-unlock' : 'fa-ban' }}"></i>
                                    {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                </button>
                            </form>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.makeAdmin', $user) }}" onsubmit="return confirm('Change this user\'s role?')">
                                @csrf
                                <button class="text-xs font-medium text-amber-600 hover:text-amber-500">
                                    <i class="fa-solid fa-user-shield"></i>
                                    {{ $user->role === 'admin' ? 'Remove Admin' : 'Make Admin' }}
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-slate-500">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="mt-4">{{ $users->withQueryString()->links() }}</div>
    @endif

</x-admin-layout>

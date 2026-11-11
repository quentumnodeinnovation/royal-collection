@extends('layouts.admin')

@section('title', 'Users')
@section('page_title', 'Users & Access Control')
@section('page_subtitle', 'Manage Royal Collection administrators and registered customer accounts.')

@section('top_actions')
    <a href="{{ route('admin.users.create') }}"
       class="px-4 py-2 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-bold hover:bg-[#2a2722] transition-colors whitespace-nowrap">
        + New User
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-[#e6dfd3] p-4 shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
    <form action="{{ route('admin.users.index') }}" method="GET"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[180px_170px_1fr_auto] gap-2.5">
        <select name="role" class="h-10 px-3 rounded-xl border border-[#d9d0c1] text-xs font-semibold text-[#141414] bg-[#faf7f2] focus:bg-white focus:outline-none focus:border-[#141414]">
            <option value="">All Roles</option>
            <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Administrators</option>
            <option value="customer" {{ $role === 'customer' ? 'selected' : '' }}>Customers</option>
        </select>

        <select name="status" class="h-10 px-3 rounded-xl border border-[#d9d0c1] text-xs font-semibold text-[#141414] bg-[#faf7f2] focus:bg-white focus:outline-none focus:border-[#141414]">
            <option value="">All Statuses</option>
            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
            <option value="blocked" {{ $status === 'blocked' ? 'selected' : '' }}>Deactivated</option>
        </select>

        <input type="text" name="q" value="{{ $search }}" placeholder="Search name, email, phone, city..."
               class="h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-xs text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414] sm:col-span-2 lg:col-span-1">

        <button type="submit" class="h-10 px-5 rounded-xl bg-[#141414] text-white text-xs font-bold hover:bg-[#2a2722] transition-colors cursor-pointer">
            Filter
        </button>
    </form>
</div>

<div class="bg-white rounded-2xl border border-[#e6dfd3] overflow-hidden shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
    {{-- Mobile User Cards --}}
    <div class="md:hidden divide-y divide-[#efe9dd]">
        @forelse($users as $user)
            <div class="p-4 space-y-2.5">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-[#141414] truncate">{{ $user->name }}</p>
                        <p class="text-[11px] text-[#787166] break-all">{{ $user->email }}</p>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase shrink-0 {{ $user->isAdmin() ? 'bg-[#141414] text-[#e3c587]' : 'bg-[#f2ece1] text-[#4a453e]' }}">
                        {{ $user->role }}
                    </span>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2 text-xs text-[#5c564e]">
                    <span>{{ $user->phone ?: '—' }} · {{ $user->city ?: '—' }}</span>
                    <span class="font-semibold text-[#141414]">{{ $user->orders_count }} orders · {{ $user->carts_count }} carts</span>
                </div>

                <div class="flex items-center justify-between gap-2 pt-2 border-t border-[#efe9dd]">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $user->is_active ? 'bg-emerald-50 text-emerald-800' : 'bg-[#f9ecee] text-[#7c232b]' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-xs font-bold text-[#9b742e] hover:underline">Edit</a>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete user {{ addslashes($user->name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-[#7c232b] hover:underline cursor-pointer">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="px-5 py-10 text-center text-xs text-[#787166]">No users matched your search.</p>
        @endforelse
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-[#faf7f2] border-b border-[#e6dfd3] text-[#6f685e] uppercase tracking-wider text-[10px]">
                <tr>
                    <th class="px-5 py-3.5">User</th>
                    <th class="px-5 py-3.5">Role</th>
                    <th class="px-5 py-3.5">Contact &amp; City</th>
                    <th class="px-5 py-3.5">Activity</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#efe9dd]">
                @forelse($users as $user)
                    <tr class="hover:bg-[#faf7f2]/70 transition-colors">
                        <td class="px-5 py-4">
                            <p class="font-bold text-[#141414]">{{ $user->name }}</p>
                            <p class="text-[11px] text-[#787166]">{{ $user->email }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $user->isAdmin() ? 'bg-[#141414] text-[#e3c587]' : 'bg-[#f2ece1] text-[#4a453e]' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-[#5c564e]">
                            <p>{{ $user->phone ?: '—' }}</p>
                            <p class="text-[11px] text-[#8f877b]">{{ $user->city ?: '—' }}</p>
                        </td>
                        <td class="px-5 py-4 text-[#5c564e]">
                            <p class="font-semibold text-[#141414]">{{ $user->orders_count }} orders · {{ $user->carts_count }} carts</p>
                            <p class="text-[11px] text-[#8f877b]">Last login: {{ optional($user->last_login_at)->diffForHumans() ?? 'Never' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $user->is_active ? 'bg-emerald-50 text-emerald-800' : 'bg-[#f9ecee] text-[#7c232b]' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right space-x-2.5 whitespace-nowrap">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-xs font-bold text-[#9b742e] hover:underline">Edit</a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete user {{ addslashes($user->name) }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-[#7c232b] hover:underline cursor-pointer">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-[#787166]">No users matched your search.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="px-5 py-3.5 border-t border-[#e6dfd3] bg-[#faf7f2]">{{ $users->links() }}</div>
    @endif
</div>
@endsection

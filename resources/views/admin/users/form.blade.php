@extends('layouts.admin')

@php $isEdit = $user->exists; @endphp
@section('title', $isEdit ? 'Edit User' : 'New User')
@section('page_title', $isEdit ? 'Edit User: '.$user->name : 'Add User Account')
@section('page_subtitle', 'Assign customer or administrator permissions and update contact details.')

@section('top_actions')
    <a href="{{ route('admin.users.index') }}"
       class="px-3.5 py-2 rounded-xl border border-[#d9d0c1] bg-white text-xs font-bold text-[#141414] hover:border-[#c69d54] transition-colors whitespace-nowrap">
        ← Back to Users
    </a>
@endsection

@section('content')
<form action="{{ $isEdit ? route('admin.users.update', $user) : route('admin.users.store') }}"
      method="POST" class="max-w-2xl bg-white rounded-2xl border border-[#e6dfd3] p-5 sm:p-6 space-y-5 shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Full Name *</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>

        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Email Address *</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>

        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Account Role *</label>
            <select name="role" required class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
                <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>

        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">City</label>
            <input type="text" name="city" value="{{ old('city', $user->city) }}"
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>

        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">
                {{ $isEdit ? 'New Password (leave blank to keep)' : 'Password *' }}
            </label>
            <input type="password" name="password" {{ $isEdit ? '' : 'required' }}
                   placeholder="Minimum 6 characters"
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>
    </div>

    <label class="inline-flex items-center gap-2 text-xs font-semibold text-[#141414] cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }} class="rounded border-[#d9d0c1] accent-[#141414]">
        <span>Active account (User can sign in)</span>
    </label>

    <div>
        <button type="submit" class="w-full sm:w-auto px-6 h-11 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-extrabold uppercase tracking-widest hover:bg-[#2a2722] transition-colors cursor-pointer">
            {{ $isEdit ? 'Save User Changes' : 'Create User Account' }}
        </button>
    </div>
</form>
@endsection

@extends('layouts.app')

@section('title', 'Create Account — Royal Collection')

@section('content')
<div class="bg-[#faf7f2] min-h-[80vh] py-10 sm:py-12 px-4 flex items-center justify-center">
    <div class="w-full max-w-lg bg-white border border-[#e6dfd3] rounded-2xl shadow-sm p-6 sm:p-8">
        <div class="text-center mb-7">
            <img src="{{ asset('images/royal-collection-logo.png') }}" alt="Royal Collection" class="h-14 w-auto mx-auto mb-3 object-contain mix-blend-multiply">
            <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-[#9b742e]">Royal Collection</p>
            <h1 class="font-editorial text-2xl sm:text-3xl font-bold text-[#141414] mt-1">Create Customer Account</h1>
            <p class="text-xs text-neutral-500 mt-1.5">Join Royal Collection for curated Mens &amp; Kids drops.</p>
        </div>

        @if($errors->any())
            <div class="mb-5 bg-[#f9ecee] border border-[#e3b8be] text-[#7c232b] text-xs font-semibold rounded-xl px-4 py-3 space-y-1">
                @foreach($errors->all() as $err)
                    <p>{{ $err }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required
                       placeholder="Aarav Sharma"
                       class="w-full h-11 px-4 rounded-xl border border-[#d9d0c1] text-sm focus:outline-none focus:border-[#9b742e]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           placeholder="you@example.com"
                           class="w-full h-11 px-4 rounded-xl border border-[#d9d0c1] text-sm focus:outline-none focus:border-[#9b742e]">
                </div>
                <div>
                    <label for="phone" class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Phone Number</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="+91 98200 11223"
                           class="w-full h-11 px-4 rounded-xl border border-[#d9d0c1] text-sm focus:outline-none focus:border-[#9b742e]">
                </div>
            </div>

            <div>
                <label for="city" class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">City</label>
                <input id="city" type="text" name="city" value="{{ old('city') }}"
                       placeholder="Mumbai"
                       class="w-full h-11 px-4 rounded-xl border border-[#d9d0c1] text-sm focus:outline-none focus:border-[#9b742e]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Password</label>
                    <input id="password" type="password" name="password" required
                           placeholder="Minimum 6 characters"
                           class="w-full h-11 px-4 rounded-xl border border-[#d9d0c1] text-sm focus:outline-none focus:border-[#9b742e]">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           placeholder="Repeat password"
                           class="w-full h-11 px-4 rounded-xl border border-[#d9d0c1] text-sm focus:outline-none focus:border-[#9b742e]">
                </div>
            </div>

            <button type="submit"
                    class="w-full h-11 mt-2 rounded-xl bg-[#141414] hover:bg-neutral-800 text-[#e3c587] text-xs font-extrabold uppercase tracking-widest transition-colors cursor-pointer">
                Create Account
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-[#e6dfd3] text-center text-xs text-neutral-600">
            Already registered?
            <a href="{{ route('login') }}" class="font-bold text-[#141414] underline underline-offset-4 hover:text-[#9b742e]">Sign in here</a>
        </div>
    </div>
</div>
@endsection

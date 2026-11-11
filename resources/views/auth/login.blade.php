@extends('layouts.app')

@section('title', 'Sign In — Royal Collection')

@section('content')
<div class="bg-[#faf7f2] min-h-[75vh] py-10 sm:py-12 px-4 flex items-center justify-center">
    <div class="w-full max-w-md bg-white border border-[#e6dfd3] rounded-2xl shadow-sm p-6 sm:p-8">
        <div class="text-center mb-7">
            <img src="{{ asset('images/royal-collection-logo.png') }}" alt="Royal Collection" class="h-14 w-auto mx-auto mb-3 object-contain mix-blend-multiply">
            <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-[#9b742e]">Royal Collection</p>
            <h1 class="font-editorial text-2xl sm:text-3xl font-bold text-[#141414] mt-1">Sign In to Your Account</h1>
            <p class="text-xs text-neutral-500 mt-1.5">Track your Mens &amp; Kids orders and enjoy faster checkout.</p>
        </div>

        @if($errors->any())
            <div class="mb-5 bg-[#f9ecee] border border-[#e3b8be] text-[#7c232b] text-xs font-semibold rounded-xl px-4 py-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.attempt') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="you@example.com"
                       class="w-full h-11 px-4 rounded-xl border border-[#d9d0c1] text-sm focus:outline-none focus:border-[#9b742e]">
            </div>

            <div>
                <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Password</label>
                <input id="password" type="password" name="password" required
                       placeholder="Enter your password"
                       class="w-full h-11 px-4 rounded-xl border border-[#d9d0c1] text-sm focus:outline-none focus:border-[#9b742e]">
            </div>

            <div class="flex flex-wrap items-center justify-between gap-2 text-xs pt-1">
                <label class="inline-flex items-center gap-2 text-neutral-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-[#d9d0c1] accent-[#141414]">
                    <span>Keep me signed in</span>
                </label>
                <a href="{{ route('admin.login') }}" class="font-semibold text-[#9b742e] hover:text-[#141414]">Admin Login →</a>
            </div>

            <button type="submit"
                    class="w-full h-11 rounded-xl bg-[#141414] hover:bg-neutral-800 text-[#e3c587] text-xs font-extrabold uppercase tracking-widest transition-colors cursor-pointer">
                Sign In
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-[#e6dfd3] text-center text-xs text-neutral-600">
            New to Royal Collection?
            <a href="{{ route('register') }}" class="font-bold text-[#141414] underline underline-offset-4 hover:text-[#9b742e]">Create an account</a>
        </div>
    </div>
</div>
@endsection

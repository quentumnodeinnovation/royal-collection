<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Royal Collection</title>
    <link rel="icon" type="image/png" href="{{ asset('images/royal-collection-logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-editorial { font-family: 'Cormorant Garamond', Georgia, serif; }
    </style>
</head>
<body class="bg-[#141414] min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-[#c69d54]/30">
        <div class="bg-[#1a1815] px-6 sm:px-8 py-6 text-center border-b border-[#c69d54]/20">
            <div class="inline-block bg-white rounded-xl px-4 py-2 mb-3">
                <img src="{{ asset('images/royal-collection-logo.png') }}" alt="Royal Collection" class="h-11 w-auto object-contain">
            </div>
            <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-[#e3c587]">Management Portal</p>
            <h1 class="font-editorial text-2xl font-bold text-white mt-1">Royal Collection Admin</h1>
        </div>

        <div class="p-6 sm:p-8">
            @if(session('error'))
                <div class="mb-4 bg-[#f9ecee] border border-[#e3b8be] text-[#7c232b] text-xs font-semibold rounded-xl px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold rounded-xl px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-[#f9ecee] border border-[#e3b8be] text-[#7c232b] text-xs font-semibold rounded-xl px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.attempt') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Admin Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', 'admin@royalcollection.in') }}" required autofocus
                           class="w-full h-11 px-4 rounded-xl border border-[#d9d0c1] text-sm focus:outline-none focus:border-[#9b742e]">
                </div>

                <div>
                    <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Password</label>
                    <input id="password" type="password" name="password" required
                           placeholder="Enter admin password"
                           class="w-full h-11 px-4 rounded-xl border border-[#d9d0c1] text-sm focus:outline-none focus:border-[#9b742e]">
                </div>

                <label class="inline-flex items-center gap-2 text-xs text-neutral-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-[#d9d0c1] accent-[#141414]">
                    <span>Remember this device</span>
                </label>

                <button type="submit"
                        class="w-full h-11 rounded-xl bg-[#141414] hover:bg-neutral-800 text-[#e3c587] text-xs font-extrabold uppercase tracking-widest transition-colors cursor-pointer">
                    Sign In to Dashboard
                </button>
            </form>

            <div class="mt-6 rounded-xl bg-[#faf7f2] border border-[#e6dfd3] p-3.5 text-[11px] text-neutral-600">
                <p class="font-bold text-[#141414] uppercase tracking-wider text-[10px] mb-1">Default Demo Admin</p>
                <p>Email: <code class="font-bold text-[#141414]">admin@royalcollection.in</code></p>
                <p>Password: <code class="font-bold text-[#141414]">Royal@2026</code></p>
            </div>

            <div class="mt-5 text-center">
                <a href="{{ route('home') }}" class="text-xs font-semibold text-[#9b742e] hover:text-[#141414]">← Back to Royal Collection Storefront</a>
            </div>
        </div>
    </div>
</body>
</html>

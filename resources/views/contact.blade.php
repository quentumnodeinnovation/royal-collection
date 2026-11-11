@extends('layouts.app')

@section('title', 'Contact Us — Royal Collection Luxury Apparel')

@section('content')
<div class="bg-[#141414] text-white py-10 sm:py-14 px-4 border-b border-[#c69d54]/25">
    <div class="max-w-[1440px] mx-auto px-0 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6 sm:gap-8 text-center md:text-left">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#e3c587]">ROYAL COLLECTION CONCIERGE</p>
            <h1 class="font-editorial text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight mt-2">Contact Us</h1>
            <p class="text-xs sm:text-sm text-neutral-300 mt-2 max-w-xl leading-relaxed">
                Questions about Mens or Kids sizing, bulk corporate orders, order status, or exchanges? Our apparel specialists are ready to assist.
            </p>
        </div>
        <div class="bg-white p-3.5 sm:p-4 rounded-xl shadow-md shrink-0">
            <img src="{{ asset('images/royal-collection-logo.png') }}" alt="Royal Collection Logo" class="h-12 sm:h-14 w-auto object-contain">
        </div>
    </div>
</div>

<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">

        {{-- Left: Studio Details & FAQs --}}
        <div class="lg:col-span-5 space-y-5 sm:space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                <div class="border border-[#e6dfd3] rounded-xl p-5 bg-[#faf7f2]">
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#9b742e]">Flagship Atelier</p>
                    <h2 class="text-sm font-extrabold text-[#141414] mt-1">Royal Collection Apparel Studio</h2>
                    <p class="text-xs text-neutral-600 mt-1.5 leading-relaxed">
                        Level 3, Imperial Arcade, Linking Road, Bandra West,<br>
                        Mumbai, Maharashtra 400050
                    </p>
                </div>

                <div class="border border-[#e6dfd3] rounded-xl p-5 bg-white space-y-2.5 text-xs">
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#9b742e]">Direct Support</p>
                    <div class="flex flex-wrap justify-between gap-2 border-b border-[#efe9dd] pb-2">
                        <span class="text-neutral-500">Email</span>
                        <span class="font-bold text-[#141414]">care@royalcollection.in</span>
                    </div>
                    <div class="flex flex-wrap justify-between gap-2 border-b border-[#efe9dd] pb-2">
                        <span class="text-neutral-500">Phone / WhatsApp</span>
                        <span class="font-bold text-[#141414]">+91 98200 11400</span>
                    </div>
                    <div class="flex flex-wrap justify-between gap-2">
                        <span class="text-neutral-500">Concierge Hours</span>
                        <span class="font-bold text-[#141414]">Mon–Sat · 10 AM – 7:30 PM</span>
                    </div>
                </div>
            </div>

            {{-- Quick FAQs --}}
            <div class="border border-[#e6dfd3] rounded-xl p-5 space-y-4 bg-white">
                <h3 class="text-xs font-extrabold uppercase tracking-widest text-[#141414]">Frequently Asked Questions</h3>
                <div class="space-y-3 text-xs">
                    <div>
                        <p class="font-bold text-[#141414]">What categories does Royal Collection carry?</p>
                        <p class="text-neutral-600 mt-0.5">We specialize exclusively in luxury Menswear (Oversized Tees, Linen Shirts, Polos, Hoodies, Jeans &amp; Cargos) and Kidswear (Ages 2Y–13Y).</p>
                    </div>
                    <div class="border-t border-[#efe9dd] pt-3">
                        <p class="font-bold text-[#141414]">How do exchanges and returns work?</p>
                        <p class="text-neutral-600 mt-0.5">We offer a 7-day hassle-free size exchange or refund with complimentary doorstep pickup on unworn items with tags intact.</p>
                    </div>
                    <div class="border-t border-[#efe9dd] pt-3">
                        <p class="font-bold text-[#141414]">When does express shipping qualify for free delivery?</p>
                        <p class="text-neutral-600 mt-0.5">All orders of ₹1,499 or above enjoy complimentary Pan-India express shipping.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Interactive Contact Form --}}
        <div class="lg:col-span-7">
            <div class="border border-[#e6dfd3] rounded-2xl p-5 sm:p-8 bg-white shadow-xs">
                <h2 class="font-editorial text-2xl sm:text-3xl font-bold text-[#141414]">Send Us a Message</h2>
                <p class="text-xs text-neutral-500 mt-1 mb-6">Fill in the form below and our team will get back to you promptly.</p>

                @if(session('contact_success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                        {{ session('contact_success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-[#f9ecee] border border-[#e3b8be] text-[#7c232b] text-xs">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Your Name *</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" required placeholder="Aarav Sharma"
                                   class="w-full h-11 px-3.5 border border-[#d9d0c1] rounded-xl text-xs font-medium focus:outline-none focus:border-[#9b742e]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required placeholder="aarav@example.com"
                                   class="w-full h-11 px-3.5 border border-[#d9d0c1] rounded-xl text-xs font-medium focus:outline-none focus:border-[#9b742e]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()?->phone) }}" placeholder="+91 98XXXXXXXX"
                                   class="w-full h-11 px-3.5 border border-[#d9d0c1] rounded-xl text-xs font-medium focus:outline-none focus:border-[#9b742e]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Order Ref (Optional)</label>
                            <input type="text" name="order_ref" value="{{ old('order_ref') }}" placeholder="e.g. RC-XXXXXXX"
                                   class="w-full h-11 px-3.5 border border-[#d9d0c1] rounded-xl text-xs font-medium focus:outline-none focus:border-[#9b742e]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Subject *</label>
                        <select name="subject" required class="w-full h-11 px-3.5 border border-[#d9d0c1] rounded-xl text-xs font-medium bg-white focus:outline-none focus:border-[#9b742e]">
                            <option value="Mens & Kids Sizing Help" @selected(old('subject') === 'Mens & Kids Sizing Help')>Mens &amp; Kids Sizing Help</option>
                            <option value="Order Status & Delivery" @selected(old('subject') === 'Order Status & Delivery')>Order Status &amp; Delivery</option>
                            <option value="Exchange / Return Request" @selected(old('subject') === 'Exchange / Return Request')>Exchange / Return Request</option>
                            <option value="Bulk / Studio Inquiry" @selected(old('subject') === 'Bulk / Studio Inquiry')>Bulk / Studio Inquiry</option>
                            <option value="General Feedback" @selected(old('subject') === 'General Feedback')>General Feedback</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-neutral-700 mb-1.5">Message *</label>
                        <textarea name="message" rows="5" required placeholder="Tell us how Royal Collection can assist you..."
                                  class="w-full p-3.5 border border-[#d9d0c1] rounded-xl text-xs font-medium focus:outline-none focus:border-[#9b742e]">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-[#141414] hover:bg-neutral-800 text-[#e3c587] text-xs font-extrabold uppercase tracking-widest rounded-xl transition-colors cursor-pointer">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

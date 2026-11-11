@extends('layouts.admin')

@section('title', 'Contact Inquiries')
@section('page_title', 'Customer Contact Inquiries')
@section('page_subtitle', 'Review questions submitted through the Contact Us page and track support responses.')

@section('content')
<div class="bg-white rounded-2xl border border-[#e6dfd3] p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-0.5">
        <a href="{{ route('admin.inquiries.index', ['q' => $search]) }}"
           class="px-3.5 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap transition-colors {{ !$status ? 'bg-[#141414] text-[#e3c587] border-[#141414]' : 'border-[#e6dfd3] text-[#4a453e] hover:border-[#c69d54]' }}">
            All Inquiries
        </a>
        @foreach($statuses as $st)
            <a href="{{ route('admin.inquiries.index', ['status' => $st, 'q' => $search]) }}"
               class="px-3.5 py-1.5 rounded-full text-xs font-bold capitalize border whitespace-nowrap transition-colors {{ $status === $st ? 'bg-[#141414] text-[#e3c587] border-[#141414]' : 'border-[#e6dfd3] text-[#4a453e] hover:border-[#c69d54]' }}">
                {{ $st }}
            </a>
        @endforeach
    </div>

    <form action="{{ route('admin.inquiries.index') }}" method="GET" class="flex items-center gap-2 w-full lg:w-auto">
        @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
        <input type="text" name="q" value="{{ $search }}" placeholder="Search name, email, subject..."
               class="h-9 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-xs text-[#141414] flex-1 lg:w-64 focus:bg-white focus:outline-none focus:border-[#141414]">
        <button type="submit" class="h-9 px-4 rounded-xl bg-[#141414] text-white text-xs font-bold hover:bg-[#2a2722] transition-colors shrink-0 cursor-pointer">
            Search
        </button>
    </form>
</div>

<div class="space-y-4">
    @forelse($inquiries as $inquiry)
        @php
            $inqBadge = match($inquiry->status) {
                'resolved' => 'bg-emerald-50 text-emerald-800 border border-emerald-200/70',
                'replied' => 'bg-sky-50 text-sky-800 border border-sky-200/70',
                default => 'bg-[#f7f1e3] text-[#8c6829] border border-[#e3c587]',
            };
        @endphp
        <div class="bg-white rounded-2xl border border-[#e6dfd3] p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
            <div class="space-y-2 min-w-0 max-w-3xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $inqBadge }}">
                        {{ $inquiry->status }}
                    </span>
                    <span class="text-sm font-extrabold text-[#141414] break-words">{{ $inquiry->subject }}</span>
                    @if($inquiry->order_ref)
                        <span class="text-[11px] font-mono px-2 py-0.5 bg-[#faf7f2] border border-[#e6dfd3] rounded text-[#4a453e]">Ref: {{ $inquiry->order_ref }}</span>
                    @endif
                </div>
                <p class="text-xs text-[#4a453e] leading-relaxed break-words">{{ $inquiry->message }}</p>
                <p class="text-[11px] text-[#787166] break-words">
                    From <span class="font-bold text-[#141414]">{{ $inquiry->name }}</span> ({{ $inquiry->email }}{{ $inquiry->phone ? ' · '.$inquiry->phone : '' }}) · {{ $inquiry->created_at->format('d M Y, h:i A') }}
                </p>
            </div>

            <div class="flex items-center gap-2.5 w-full lg:w-auto pt-3 lg:pt-0 border-t lg:border-t-0 border-[#efe9dd] shrink-0">
                <form action="{{ route('admin.inquiries.status', $inquiry) }}" method="POST" class="flex-1 lg:flex-none flex items-center m-0">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.submit()"
                            class="w-full lg:w-36 h-9 px-3 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-xs font-bold text-[#141414] capitalize focus:bg-white focus:outline-none focus:border-[#141414] cursor-pointer">
                        @foreach($statuses as $st)
                            <option value="{{ $st }}" {{ $inquiry->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </form>

                <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" class="flex items-center m-0 shrink-0" onsubmit="return confirm('Delete this inquiry?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3.5 h-9 rounded-xl border border-[#e3b8be] text-[#7c232b] text-xs font-bold hover:bg-[#f9ecee] transition-colors shrink-0 cursor-pointer">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-dashed border-[#d9d0c1] p-10 text-center text-xs text-[#787166]">
            No customer inquiries found.
        </div>
    @endforelse

    @if($inquiries->hasPages())
        <div class="pt-1">{{ $inquiries->links() }}</div>
    @endif
</div>
@endsection

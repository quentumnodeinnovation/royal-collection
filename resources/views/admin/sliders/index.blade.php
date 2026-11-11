@extends('layouts.admin')

@section('title', 'Hero Sliders')
@section('page_title', 'Homepage Hero Sliders')
@section('page_subtitle', 'Manage the live rotating banner carousel displayed at the top of the Royal Collection homepage.')

@section('top_actions')
    <a href="{{ route('admin.sliders.create') }}"
       class="px-4 py-2 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-bold hover:bg-[#2a2722] transition-colors whitespace-nowrap">
        + New Slider
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 gap-5">
    @forelse($sliders as $slider)
        <div class="bg-white rounded-2xl border border-[#e6dfd3] overflow-hidden flex flex-col md:flex-row shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
            <div class="md:w-72 h-48 md:h-auto shrink-0 bg-[#141414] relative">
                <img src="{{ asset($slider->image) }}" alt="{{ $slider->title }}" class="w-full h-full object-cover">
                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-[#141414]/90 border border-[#c69d54]/40 text-[#e3c587] text-[10px] font-bold">
                    Order #{{ $slider->sort_order }}
                </span>
            </div>

            <div class="flex-1 p-5 flex flex-col justify-between gap-4 min-w-0">
                <div>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#9b742e]">{{ $slider->eyebrow ?: 'HERO BANNER' }}</span>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $slider->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/70' : 'bg-[#f2ece1] text-[#6f685e]' }}">
                            {{ $slider->is_active ? 'Live on Homepage' : 'Hidden' }}
                        </span>
                    </div>
                    <h2 class="font-editorial text-xl sm:text-2xl font-bold text-[#141414] mt-1 leading-snug">{{ $slider->title }}</h2>
                    <p class="text-xs text-[#5c564e] mt-1 leading-relaxed">{{ $slider->subtitle }}</p>
                    <div class="flex flex-wrap gap-2 mt-3 text-[11px]">
                        @if($slider->primary_cta_label)
                            <span class="px-2.5 py-1 rounded-lg bg-[#faf7f2] border border-[#e6dfd3] font-semibold text-[#141414] break-all">
                                CTA 1: {{ $slider->primary_cta_label }} → {{ $slider->primary_cta_url }}
                            </span>
                        @endif
                        @if($slider->secondary_cta_label)
                            <span class="px-2.5 py-1 rounded-lg bg-[#faf7f2] border border-[#e6dfd3] font-semibold text-[#141414] break-all">
                                CTA 2: {{ $slider->secondary_cta_label }} → {{ $slider->secondary_cta_url }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-[#efe9dd]">
                    <form action="{{ route('admin.sliders.toggle', $slider) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-3.5 py-1.5 rounded-lg border border-[#d9d0c1] bg-[#faf7f2] text-xs font-bold text-[#141414] hover:border-[#c69d54] transition-colors cursor-pointer">
                            {{ $slider->is_active ? 'Hide Slider' : 'Activate Slider' }}
                        </button>
                    </form>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.sliders.edit', $slider) }}" class="text-xs font-bold text-[#9b742e] hover:underline">Edit Slider</a>
                        <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" onsubmit="return confirm('Delete slider {{ addslashes($slider->title) }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-[#7c232b] hover:underline cursor-pointer">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-dashed border-[#d9d0c1] p-12 text-center">
            <p class="text-sm font-bold text-[#141414]">No hero sliders configured</p>
            <a href="{{ route('admin.sliders.create') }}" class="inline-block mt-3 px-4 py-2 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-bold">+ Add First Slider</a>
        </div>
    @endforelse
</div>
@endsection

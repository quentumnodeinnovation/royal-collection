@extends('layouts.admin')

@php $isEdit = $slider->exists; @endphp
@section('title', $isEdit ? 'Edit Slider' : 'New Slider')
@section('page_title', $isEdit ? 'Edit Hero Slider' : 'Add Homepage Hero Slider')
@section('page_subtitle', 'Changes immediately update the Royal Collection storefront carousel.')

@section('top_actions')
    <a href="{{ route('admin.sliders.index') }}"
       class="px-3.5 py-2 rounded-xl border border-[#d9d0c1] bg-white text-xs font-bold text-[#141414] hover:border-[#c69d54] transition-colors whitespace-nowrap">
        ← Back to Sliders
    </a>
@endsection

@section('content')
<form action="{{ $isEdit ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}"
      method="POST" enctype="multipart/form-data" class="max-w-4xl bg-white rounded-2xl border border-[#e6dfd3] p-5 sm:p-6 space-y-5 shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
    @csrf
    @if($isEdit) @method('PUT') @endif

    @if($slider->image)
        <div class="rounded-xl overflow-hidden border border-[#e6dfd3] bg-[#141414] max-h-56">
            <img src="{{ asset($slider->image) }}" alt="{{ $slider->title }}" class="w-full h-52 object-cover">
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Eyebrow Label</label>
            <input type="text" name="eyebrow" value="{{ old('eyebrow', $slider->eyebrow) }}"
                   placeholder="ROYAL COLLECTION · MENS LUXURY DROP"
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>

        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Display Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $slider->sort_order ?? 1) }}" min="0"
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>
    </div>

    <div>
        <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Headline Title *</label>
        <input type="text" name="title" value="{{ old('title', $slider->title) }}" required
               placeholder="Heavyweight Essentials Crafted for Royal Presence."
               class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
    </div>

    <div>
        <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Subtitle</label>
        <textarea name="subtitle" rows="2" class="w-full p-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">{{ old('subtitle', $slider->subtitle) }}</textarea>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Primary Button Text</label>
            <input type="text" name="primary_cta_label" value="{{ old('primary_cta_label', $slider->primary_cta_label) }}"
                   placeholder="Shop Menswear"
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>
        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Primary Button URL</label>
            <input type="text" name="primary_cta_url" value="{{ old('primary_cta_url', $slider->primary_cta_url) }}"
                   placeholder="/men"
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>
        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Secondary Button Text</label>
            <input type="text" name="secondary_cta_label" value="{{ old('secondary_cta_label', $slider->secondary_cta_label) }}"
                   placeholder="Explore All"
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>
        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Secondary Button URL</label>
            <input type="text" name="secondary_cta_url" value="{{ old('secondary_cta_url', $slider->secondary_cta_url) }}"
                   placeholder="/shop"
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Upload Banner Image</label>
            <input type="file" name="image_file" accept="image/*"
                   class="w-full text-xs text-[#4a453e] file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:bg-[#141414] file:text-[#e3c587] file:font-bold hover:file:bg-[#2a2722] cursor-pointer">
        </div>
        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Or Banner Image Path</label>
            <input type="text" name="image_path" value="{{ old('image_path', $slider->image) }}"
                   placeholder="/images/banners/hero-mens.jpg"
                   class="w-full h-10 px-3 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-xs text-[#141414] font-mono focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>
    </div>

    <label class="inline-flex items-center gap-2 text-xs font-semibold text-[#141414] cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }} class="rounded border-[#d9d0c1] accent-[#141414]">
        <span>Active (Show live in homepage hero slider)</span>
    </label>

    <div>
        <button type="submit" class="w-full sm:w-auto px-6 h-11 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-extrabold uppercase tracking-widest hover:bg-[#2a2722] transition-colors cursor-pointer">
            {{ $isEdit ? 'Save Slider Changes' : 'Create Hero Slider' }}
        </button>
    </div>
</form>
@endsection

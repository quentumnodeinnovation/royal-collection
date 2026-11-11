@extends('layouts.admin')

@php $isEdit = $category->exists; @endphp
@section('title', $isEdit ? 'Edit Category' : 'New Category')
@section('page_title', $isEdit ? 'Edit Category: '.$category->name : 'Create Category (Mens or Kids)')
@section('page_subtitle', 'Categories stay strictly focused on Royal Collection Mens and Kids collections.')

@section('top_actions')
    <a href="{{ route('admin.categories.index') }}"
       class="px-3.5 py-2 rounded-xl border border-[#d9d0c1] bg-white text-xs font-bold text-[#141414] hover:border-[#c69d54] transition-colors whitespace-nowrap">
        ← Back
    </a>
@endsection

@section('content')
<form action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
      method="POST" enctype="multipart/form-data" class="max-w-3xl bg-white rounded-2xl border border-[#e6dfd3] p-5 sm:p-6 space-y-5 shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Category Name *</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                   placeholder="e.g., Oversized T-Shirts"
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>

        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Audience *</label>
            <select name="audience" required class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
                <option value="men" {{ old('audience', $category->audience) === 'men' ? 'selected' : '' }}>Mens Collection</option>
                <option value="kids" {{ old('audience', $category->audience) === 'kids' ? 'selected' : '' }}>Kids Collection</option>
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Group Type *</label>
            <select name="group_type" required class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
                <option value="topwear" {{ old('group_type', $category->group_type) === 'topwear' ? 'selected' : '' }}>Topwear</option>
                <option value="bottomwear" {{ old('group_type', $category->group_type) === 'bottomwear' ? 'selected' : '' }}>Bottomwear</option>
                <option value="sets" {{ old('group_type', $category->group_type) === 'sets' ? 'selected' : '' }}>Co-Ord Sets</option>
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0"
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>
    </div>

    <div>
        <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Tagline</label>
        <input type="text" name="tagline" value="{{ old('tagline', $category->tagline) }}"
               placeholder="240 GSM drop-shoulder luxury tees"
               class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
    </div>

    <div>
        <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Description</label>
        <textarea name="description" rows="3" class="w-full p-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">{{ old('description', $category->description) }}</textarea>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Upload Cover Image</label>
            <input type="file" name="image_file" accept="image/*"
                   class="w-full text-xs text-[#4a453e] file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:bg-[#141414] file:text-[#e3c587] file:font-bold hover:file:bg-[#2a2722] cursor-pointer">
        </div>
        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Or Existing Image Path</label>
            <input type="text" name="image_path" value="{{ old('image_path', $category->image) }}"
                   placeholder="/images/products/men-hoodie-noir.jpg"
                   class="w-full h-10 px-3 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-xs text-[#141414] font-mono focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>
    </div>

    <label class="inline-flex items-center gap-2 text-xs font-semibold text-[#141414] cursor-pointer">
        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }} class="rounded border-[#d9d0c1] accent-[#141414]">
        <span>Highlight on Homepage Category Showcase</span>
    </label>

    <div class="pt-2">
        <button type="submit" class="w-full sm:w-auto px-6 h-11 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-extrabold uppercase tracking-widest hover:bg-[#2a2722] transition-colors cursor-pointer">
            {{ $isEdit ? 'Update Category' : 'Create Category' }}
        </button>
    </div>
</form>
@endsection

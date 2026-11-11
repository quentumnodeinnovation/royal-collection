@extends('layouts.admin')

@php $isEdit = $product->exists; @endphp
@section('title', $isEdit ? 'Edit Product' : 'New Product')
@section('page_title', $isEdit ? 'Edit: '.$product->name : 'Add Mens or Kids Product')
@section('page_subtitle', 'Configure pricing, category (Mens/Kids only), sizes, badges, and product photography.')

@section('top_actions')
    <a href="{{ route('admin.products.index') }}"
       class="px-3.5 py-2 rounded-xl border border-[#d9d0c1] bg-white text-xs font-bold text-[#141414] hover:border-[#c69d54] transition-colors whitespace-nowrap">
        ← Back to Catalog
    </a>
@endsection

@section('content')
<form action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}"
      method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="lg:col-span-2 bg-white rounded-2xl border border-[#e6dfd3] p-5 sm:p-6 space-y-5 shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Product Name *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                       placeholder="e.g., Royal Crest Oversized Tee: Espresso"
                       class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Category (Mens or Kids) *</label>
                <select name="category_id" required class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
                    @foreach(['men' => 'MENS COLLECTION', 'kids' => 'KIDS COLLECTION'] as $audKey => $audLabel)
                        <optgroup label="{{ $audLabel }}">
                            @foreach($categories->where('audience', $audKey) as $cat)
                                <option value="{{ $cat->id }}" {{ (string) old('category_id', $product->category_id) === (string) $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ ucfirst($cat->group_type) }})
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">SKU *</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required
                       placeholder="RC-MEN-OT-010"
                       class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] uppercase focus:bg-white focus:outline-none focus:border-[#141414]">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Selling Price (₹) *</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" min="99" required
                       class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">MRP (₹)</label>
                <input type="number" name="mrp" value="{{ old('mrp', $product->mrp) }}" min="99"
                       class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Stock Quantity *</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 25) }}" min="0" required
                       class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Subtitle</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $product->subtitle) }}"
                       placeholder="Oversized T-Shirts"
                       class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Fit Badge</label>
                <input type="text" name="fit_badge" value="{{ old('fit_badge', $product->fit_badge) }}"
                       placeholder="OVERSIZED FIT"
                       class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Fabric Badge</label>
                <input type="text" name="fabric_badge" value="{{ old('fabric_badge', $product->fabric_badge) }}"
                       placeholder="240 GSM HEAVY GAUGE"
                       class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Sizes (comma separated)</label>
                <input type="text" name="sizes_input" value="{{ old('sizes_input', is_array($product->sizes) ? implode(', ', $product->sizes) : '') }}"
                       placeholder="S, M, L, XL, XXL"
                       class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Color Name</label>
                <input type="text" name="color_name" value="{{ old('color_name', $product->color_name ?? 'Obsidian Black') }}"
                       class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Color Swatch Hex</label>
                <input type="text" name="color_hex" value="{{ old('color_hex', $product->color_hex ?? '#141414') }}"
                       class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
            </div>
        </div>

        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Product Description *</label>
            <textarea name="description" rows="4" required
                      class="w-full p-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">{{ old('description', $product->description) }}</textarea>
        </div>

        <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-[#5c564e] mb-1.5">Fabric Care &amp; Details</label>
            <input type="text" name="fabric_details" value="{{ old('fabric_details', $product->fabric_details) }}"
                   placeholder="100% Combed Ring-Spun Cotton · Bio-washed · Pre-shrunk"
                   class="w-full h-10 px-3.5 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-sm text-[#141414] focus:bg-white focus:outline-none focus:border-[#141414]">
        </div>
    </div>

    {{-- Right Sidebar: Image & Flags --}}
    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-[#e6dfd3] p-5 sm:p-6 space-y-4 shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
            <h2 class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#141414]">Product Image</h2>

            @if($product->image)
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-56 object-cover rounded-xl bg-[#f2ece1] border border-[#e6dfd3]">
            @endif

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#6f685e] mb-1.5">Upload New Photo</label>
                <input type="file" name="image_file" accept="image/*"
                       class="w-full text-xs text-[#4a453e] file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:bg-[#141414] file:text-[#e3c587] file:font-bold hover:file:bg-[#2a2722] cursor-pointer">
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#6f685e] mb-1.5">Or Existing Image Path</label>
                <input type="text" name="image_path" value="{{ old('image_path', $product->image) }}"
                       placeholder="/images/products/men-hoodie-noir.jpg"
                       class="w-full h-10 px-3 rounded-xl border border-[#d9d0c1] bg-[#faf7f2] text-xs text-[#141414] font-mono focus:bg-white focus:outline-none focus:border-[#141414]">
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#e6dfd3] p-5 sm:p-6 space-y-3 shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
            <h2 class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#141414]">Merchandising Visibility</h2>
            <label class="flex items-center gap-2.5 text-xs font-semibold text-[#141414] cursor-pointer">
                <input type="checkbox" name="is_trending" value="1" {{ old('is_trending', $product->is_trending) ? 'checked' : '' }} class="rounded border-[#d9d0c1] accent-[#141414]">
                <span>Show in Homepage Trending Drops</span>
            </label>
            <label class="flex items-center gap-2.5 text-xs font-semibold text-[#141414] cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-[#d9d0c1] accent-[#141414]">
                <span>Mark as Featured Luxury Pick</span>
            </label>
        </div>

        <button type="submit"
                class="w-full h-12 rounded-xl bg-[#141414] hover:bg-[#2a2722] text-[#e3c587] text-xs font-extrabold uppercase tracking-widest transition-colors cursor-pointer">
            {{ $isEdit ? 'Save Product Changes' : 'Publish Product to Store' }}
        </button>
    </div>
</form>
@endsection

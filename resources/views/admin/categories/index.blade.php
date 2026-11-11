@extends('layouts.admin')

@section('title', 'Categories')
@section('page_title', 'Categories (Mens & Kids Only)')
@section('page_subtitle', 'Organize Royal Collection Menswear and Kidswear product sections and homepage category cards.')

@section('top_actions')
    <a href="{{ route('admin.categories.create') }}"
       class="px-4 py-2 rounded-xl bg-[#141414] text-[#e3c587] text-xs font-bold hover:bg-[#2a2722] transition-colors whitespace-nowrap">
        + New Category
    </a>
@endsection

@section('content')
<div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
    <a href="{{ route('admin.categories.index') }}"
       class="px-3.5 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap transition-colors {{ !$audience ? 'bg-[#141414] text-[#e3c587] border-[#141414]' : 'bg-white border-[#e6dfd3] text-[#4a453e] hover:border-[#c69d54]' }}">
        All Sections
    </a>
    <a href="{{ route('admin.categories.index', ['audience' => 'men']) }}"
       class="px-3.5 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap transition-colors {{ $audience === 'men' ? 'bg-[#141414] text-[#e3c587] border-[#141414]' : 'bg-white border-[#e6dfd3] text-[#4a453e] hover:border-[#c69d54]' }}">
        Mens Only
    </a>
    <a href="{{ route('admin.categories.index', ['audience' => 'kids']) }}"
       class="px-3.5 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap transition-colors {{ $audience === 'kids' ? 'bg-[#141414] text-[#e3c587] border-[#141414]' : 'bg-white border-[#e6dfd3] text-[#4a453e] hover:border-[#c69d54]' }}">
        Kids Only
    </a>
</div>

<div class="bg-white rounded-2xl border border-[#e6dfd3] overflow-hidden shadow-[0_10px_25px_-20px_rgba(20,20,20,0.12)]">
    {{-- Mobile Cards --}}
    <div class="md:hidden divide-y divide-[#efe9dd]">
        @forelse($categories as $category)
            <div class="p-4 space-y-3">
                <div class="flex items-start gap-3">
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                         class="w-12 h-14 rounded-lg object-cover bg-[#f2ece1] border border-[#e6dfd3] shrink-0">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-bold text-[#141414]">{{ $category->name }}</p>
                            <span class="px-2 py-0.5 rounded bg-[#141414] text-[#e3c587] text-[10px] font-bold uppercase shrink-0">{{ $category->audience }}</span>
                        </div>
                        @if($category->tagline)
                            <p class="text-[11px] text-[#787166] mt-0.5 line-clamp-2">{{ $category->tagline }}</p>
                        @endif
                        <div class="flex flex-wrap items-center gap-2 mt-2 text-[11px]">
                            <span class="capitalize font-semibold text-[#4a453e]">{{ $category->group_type }}</span>
                            <span class="text-[#b8b0a4]">·</span>
                            <span class="font-bold text-[#141414]">{{ $category->products_count }} items</span>
                            <span class="text-[#b8b0a4]">·</span>
                            <span class="text-[#787166]">#{{ $category->sort_order }}</span>
                            <span class="ml-auto px-2 py-0.5 rounded-full text-[10px] font-bold {{ $category->is_featured ? 'bg-[#f7f1e3] text-[#8c6829]' : 'bg-[#f2ece1] text-[#6f685e]' }}">
                                {{ $category->is_featured ? 'Featured' : 'Standard' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-4 pt-2 border-t border-[#efe9dd]">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs font-bold text-[#9b742e] hover:underline">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete category {{ addslashes($category->name) }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-[#7c232b] hover:underline cursor-pointer">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="px-5 py-10 text-center text-xs text-[#787166]">No categories found.</p>
        @endforelse
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-[#faf7f2] border-b border-[#e6dfd3] text-[#6f685e] uppercase tracking-wider text-[10px]">
                <tr>
                    <th class="px-5 py-3.5">Category</th>
                    <th class="px-5 py-3.5">Audience</th>
                    <th class="px-5 py-3.5">Group</th>
                    <th class="px-5 py-3.5">Products</th>
                    <th class="px-5 py-3.5">Sort</th>
                    <th class="px-5 py-3.5">Featured</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#efe9dd]">
                @forelse($categories as $category)
                    <tr class="hover:bg-[#faf7f2]/70 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                     class="w-11 h-12 rounded-lg object-cover bg-[#f2ece1] border border-[#e6dfd3] shrink-0">
                                <div>
                                    <p class="font-bold text-[#141414]">{{ $category->name }}</p>
                                    <p class="text-[11px] text-[#787166]">{{ $category->tagline }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded bg-[#141414] text-[#e3c587] text-[10px] font-bold uppercase">{{ $category->audience }}</span>
                        </td>
                        <td class="px-5 py-3.5 capitalize font-semibold text-[#4a453e]">{{ $category->group_type }}</td>
                        <td class="px-5 py-3.5 font-bold text-[#141414]">{{ $category->products_count }} items</td>
                        <td class="px-5 py-3.5 text-[#787166]">#{{ $category->sort_order }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $category->is_featured ? 'bg-[#f7f1e3] text-[#8c6829]' : 'bg-[#f2ece1] text-[#6f685e]' }}">
                                {{ $category->is_featured ? 'Featured' : 'Standard' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-2.5 whitespace-nowrap">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs font-bold text-[#9b742e] hover:underline">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete category {{ addslashes($category->name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-[#7c232b] hover:underline cursor-pointer">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-[#787166]">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
        <div class="px-5 py-3.5 border-t border-[#e6dfd3] bg-[#faf7f2]">{{ $categories->links() }}</div>
    @endif
</div>
@endsection

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends AdminBaseController
{
    public function index(Request $request): View
    {
        $audience = $request->query('audience');

        $categories = Category::query()
            ->withCount('products')
            ->when(in_array($audience, ['men', 'kids'], true), fn ($q) => $q->where('audience', $audience))
            ->orderBy('audience')
            ->orderBy('sort_order')
            ->paginate(20)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'audience'));
    }

    public function create(): View
    {
        $category = new Category([
            'audience' => 'men',
            'group_type' => 'topwear',
            'sort_order' => Category::max('sort_order') + 1,
            'is_featured' => true,
        ]);

        return view('admin.categories.form', compact('category'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCategory($request);

        $imagePath = $this->resolveImage(
            $request,
            'image_file',
            'image_path',
            'products',
            null,
            '/images/products/men-hoodie-noir.jpg'
        );

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug(Category::class, "{$validated['audience']}-{$validated['name']}"),
            'audience' => $validated['audience'],
            'group_type' => $validated['group_type'],
            'image' => $imagePath,
            'tagline' => $validated['tagline'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Category \"{$category->name}\" created.");
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $this->validateCategory($request);

        $imagePath = $this->resolveImage(
            $request,
            'image_file',
            'image_path',
            'products',
            $category->image
        );

        $slug = ($category->name !== $validated['name'] || $category->audience !== $validated['audience'])
            ? $this->uniqueSlug(Category::class, "{$validated['audience']}-{$validated['name']}", $category->id)
            : $category->slug;

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'audience' => $validated['audience'],
            'group_type' => $validated['group_type'],
            'image' => $imagePath,
            'tagline' => $validated['tagline'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        if ($category->wasChanged('audience')) {
            $category->products()->update(['audience' => $validated['audience']]);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Category \"{$category->name}\" updated.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('error', "Cannot delete \"{$category->name}\" while it still has products. Move or delete its products first.");
        }

        $name = $category->name;
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Category \"{$name}\" deleted.");
    }

    private function validateCategory(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'audience' => ['required', Rule::in(['men', 'kids'])],
            'group_type' => ['required', Rule::in(['topwear', 'bottomwear', 'sets'])],
            'tagline' => ['nullable', 'string', 'max:160'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image_file' => ['nullable', 'image', 'max:4096'],
            'image_path' => ['nullable', 'string', 'max:255'],
        ]);
    }
}

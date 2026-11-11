<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends AdminBaseController
{
    public function index(Request $request): View
    {
        $audience = $request->query('audience');
        $categoryId = $request->query('category_id');
        $stockState = $request->query('stock');
        $search = trim((string) $request->query('q', ''));

        $products = Product::query()
            ->with('category')
            ->when(in_array($audience, ['men', 'kids'], true), fn ($q) => $q->where('audience', $audience))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($stockState === 'low', fn ($q) => $q->where('stock', '>', 0)->where('stock', '<=', 10))
            ->when($stockState === 'out', fn ($q) => $q->where('stock', '<=', 0))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('subtitle', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('audience')->orderBy('sort_order')->get();

        return view('admin.products.index', compact(
            'products',
            'categories',
            'audience',
            'categoryId',
            'stockState',
            'search'
        ));
    }

    public function create(): View
    {
        $product = new Product([
            'audience' => 'men',
            'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
            'stock' => 25,
            'is_trending' => false,
            'is_featured' => false,
        ]);
        $categories = Category::orderBy('audience')->orderBy('sort_order')->get();

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);
        $category = Category::findOrFail($validated['category_id']);

        $audience = $category->audience;
        $imagePath = $this->resolveImage(
            $request,
            'image_file',
            'image_path',
            'products',
            null,
            '/images/products/men-hoodie-noir.jpg'
        );

        $product = Product::create([
            'category_id' => $category->id,
            'audience' => $audience,
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug(Product::class, $validated['name']),
            'sku' => strtoupper($validated['sku']),
            'subtitle' => ($validated['subtitle'] ?? null) ?: $category->name,
            'fit_badge' => $validated['fit_badge'] ?? null,
            'fabric_badge' => $validated['fabric_badge'] ?? null,
            'price' => $validated['price'],
            'mrp' => $validated['mrp'] ?: $validated['price'],
            'image' => $imagePath,
            'color_name' => $validated['color_name'] ?? 'Obsidian Black',
            'color_hex' => $validated['color_hex'] ?? '#111111',
            'sizes' => $this->parseSizes($request->input('sizes_input'), $audience),
            'description' => $validated['description'],
            'fabric_details' => $validated['fabric_details'] ?? null,
            'is_trending' => $request->boolean('is_trending'),
            'is_featured' => $request->boolean('is_featured'),
            'stock' => $validated['stock'],
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Product \"{$product->name}\" created successfully.");
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('audience')->orderBy('sort_order')->get();

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateProduct($request, $product);
        $category = Category::findOrFail($validated['category_id']);
        $audience = $category->audience;

        $imagePath = $this->resolveImage(
            $request,
            'image_file',
            'image_path',
            'products',
            $product->image
        );

        $slug = $product->name !== $validated['name']
            ? $this->uniqueSlug(Product::class, $validated['name'], $product->id)
            : $product->slug;

        $product->update([
            'category_id' => $category->id,
            'audience' => $audience,
            'name' => $validated['name'],
            'slug' => $slug,
            'sku' => strtoupper($validated['sku']),
            'subtitle' => ($validated['subtitle'] ?? null) ?: $category->name,
            'fit_badge' => $validated['fit_badge'] ?? null,
            'fabric_badge' => $validated['fabric_badge'] ?? null,
            'price' => $validated['price'],
            'mrp' => $validated['mrp'] ?: $validated['price'],
            'image' => $imagePath,
            'color_name' => $validated['color_name'] ?? 'Obsidian Black',
            'color_hex' => $validated['color_hex'] ?? '#111111',
            'sizes' => $this->parseSizes($request->input('sizes_input'), $audience),
            'description' => $validated['description'],
            'fabric_details' => $validated['fabric_details'] ?? null,
            'is_trending' => $request->boolean('is_trending'),
            'is_featured' => $request->boolean('is_featured'),
            'stock' => $validated['stock'],
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Product \"{$product->name}\" updated.");
    }

    public function toggleFlag(Product $product, string $flag): RedirectResponse
    {
        if (!in_array($flag, ['is_trending', 'is_featured'], true)) {
            abort(404);
        }

        $product->update([$flag => !$product->{$flag}]);

        return back()->with('success', "Updated \"{$product->name}\".");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Product \"{$name}\" removed from catalog.");
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:180'],
            'sku' => [
                'required',
                'string',
                'max:60',
                Rule::unique('products', 'sku')->ignore($product?->id),
            ],
            'subtitle' => ['nullable', 'string', 'max:180'],
            'fit_badge' => ['nullable', 'string', 'max:80'],
            'fabric_badge' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:99'],
            'mrp' => ['nullable', 'integer', 'min:99'],
            'color_name' => ['nullable', 'string', 'max:60'],
            'color_hex' => ['nullable', 'string', 'max:20'],
            'sizes_input' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'fabric_details' => ['nullable', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'image_file' => ['nullable', 'image', 'max:4096'],
            'image_path' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function parseSizes(?string $input, string $audience): array
    {
        $parsed = array_values(array_filter(array_map('trim', explode(',', (string) $input))));

        if (!empty($parsed)) {
            return $parsed;
        }

        return $audience === 'kids'
            ? ['2-3Y', '4-5Y', '6-7Y', '8-9Y', '10-12Y']
            : ['S', 'M', 'L', 'XL', 'XXL'];
    }
}

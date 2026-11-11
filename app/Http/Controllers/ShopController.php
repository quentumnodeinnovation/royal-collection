<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * Render the catalog / shop page for All, Men, Kids, or Category views.
     */
    public function index(Request $request, ?string $audience = null, ?string $categorySlug = null): View
    {
        $activeAudience = $audience ?? $request->query('audience', 'all');
        if (!in_array($activeAudience, ['all', 'men', 'kids'], true)) {
            $activeAudience = 'all';
        }

        $activeCategorySlug = $categorySlug ?? $request->query('category');
        $activeCategory = null;
        if ($activeCategorySlug) {
            $activeCategory = Category::where('slug', $activeCategorySlug)->first();
            if ($activeCategory && $activeAudience === 'all') {
                $activeAudience = $activeCategory->audience;
            }
        }

        $search = trim((string) $request->query('q', ''));
        $sort = (string) $request->query('sort', 'trending');
        $selectedSize = trim((string) $request->query('size', ''));
        $maxPrice = $request->integer('max_price');
        $onlyTrending = $request->boolean('trending');

        $query = Product::with('category')->audience($activeAudience === 'all' ? null : $activeAudience);

        if ($activeCategory) {
            $query->where('category_id', $activeCategory->id);
        }

        if ($onlyTrending) {
            $query->where('is_trending', true);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $like = '%' . $search . '%';
                $q->where('name', 'like', $like)
                    ->orWhere('subtitle', 'like', $like)
                    ->orWhere('color_name', 'like', $like)
                    ->orWhere('fit_badge', 'like', $like)
                    ->orWhere('description', 'like', $like);
            });
        }

        if ($selectedSize !== '') {
            $query->whereJsonContains('sizes', $selectedSize);
        }

        if ($maxPrice > 0) {
            $query->where('price', '<=', $maxPrice);
        }

        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'newest' => $query->orderByDesc('id'),
            default => $query->orderByDesc('is_trending')->orderByDesc('is_featured')->orderBy('id'),
        };

        $products = $query->get();

        $availableCategories = Category::when(
            $activeAudience !== 'all',
            fn ($q) => $q->where('audience', $activeAudience)
        )->orderBy('audience')->orderBy('sort_order')->get();

        $availableSizes = $activeAudience === 'kids'
            ? ['2-3Y', '4-5Y', '6-7Y', '8-9Y', '10-11Y', '12-13Y']
            : ($activeAudience === 'men'
                ? ['S', 'M', 'L', 'XL', 'XXL', '28', '30', '32', '34', '36']
                : ['S', 'M', 'L', 'XL', 'XXL', '30', '32', '34', '4-5Y', '6-7Y', '8-9Y', '10-11Y']);

        return view('shop', [
            'products' => $products,
            'activeAudience' => $activeAudience,
            'activeCategory' => $activeCategory,
            'availableCategories' => $availableCategories,
            'availableSizes' => $availableSizes,
            'search' => $search,
            'sort' => $sort,
            'selectedSize' => $selectedSize,
            'maxPrice' => $maxPrice,
            'onlyTrending' => $onlyTrending,
        ]);
    }

    /**
     * Render a dedicated product detail page.
     */
    public function show(string $slug): View
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::with('category')
            ->where('audience', $product->audience)
            ->where('id', '!=', $product->id)
            ->orderByDesc('is_trending')
            ->take(4)
            ->get();

        return view('product-detail', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}

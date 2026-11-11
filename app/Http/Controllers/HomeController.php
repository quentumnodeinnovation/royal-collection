<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Render the Royal Collection homepage inspired by modern streetwear storefronts.
     */
    public function index(): View
    {
        $sliders = Slider::active()->get();
        $menCategories = Category::forAudience('men')->get();
        $kidsCategories = Category::forAudience('kids')->get();

        $trendingProducts = Product::with('category')
            ->where('is_trending', true)
            ->orderByDesc('is_featured')
            ->take(8)
            ->get();

        $kidsShowcase = Product::with('category')
            ->where('audience', 'kids')
            ->orderByDesc('is_featured')
            ->take(4)
            ->get();

        $allProducts = Product::with('category')
            ->orderByDesc('is_trending')
            ->orderByDesc('is_featured')
            ->get();

        return view('home', [
            'sliders' => $sliders,
            'menCategories' => $menCategories,
            'kidsCategories' => $kidsCategories,
            'trendingProducts' => $trendingProducts,
            'kidsShowcase' => $kidsShowcase,
            'allProducts' => $allProducts,
        ]);
    }
}

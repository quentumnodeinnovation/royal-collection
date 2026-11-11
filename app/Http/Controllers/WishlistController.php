<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\StorefrontService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(private readonly StorefrontService $storefront)
    {
    }

    /**
     * Show products saved in the shopper's wishlist.
     */
    public function index(): View
    {
        $ids = $this->storefront->getWishlistIds();
        $products = empty($ids)
            ? collect()
            : Product::with('category')->whereIn('id', $ids)->get();

        return view('wishlist', ['products' => $products]);
    }

    /**
     * Toggle a product in or out of the session wishlist.
     */
    public function toggle(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $id = (int) $validated['product_id'];
        $ids = $this->storefront->getWishlistIds();

        if (in_array($id, $ids, true)) {
            $ids = array_values(array_diff($ids, [$id]));
            $saved = false;
        } else {
            $ids[] = $id;
            $saved = true;
        }

        session(['rc_wishlist' => $ids]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'saved' => $saved,
                'count' => count($ids),
            ]);
        }

        return back()->with('success', $saved ? 'Added to your wishlist.' : 'Removed from your wishlist.');
    }
}

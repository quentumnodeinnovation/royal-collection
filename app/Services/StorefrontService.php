<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StorefrontService
{
    public const FREE_SHIPPING_THRESHOLD = 1499;
    public const STANDARD_SHIPPING_FEE = 99;

    /**
     * Get Men and Kids navigation categories grouped by audience.
     *
     * @return array{men: Collection<int, Category>, kids: Collection<int, Category>}
     */
    public function getDrawerCategories(): array
    {
        $categories = Category::orderBy('sort_order')->get();

        return [
            'men' => $categories->where('audience', 'men')->values(),
            'kids' => $categories->where('audience', 'kids')->values(),
        ];
    }

    /**
     * Build hydrated cart summary from the current session.
     *
     * @return array{items: array<int, array<string, mixed>>, count: int, subtotal: int, shipping: int, total: int, freeShippingRemaining: int}
     */
    public function getCartSummary(): array
    {
        $rawCart = session('rc_cart', []);
        if (empty($rawCart)) {
            return [
                'items' => [],
                'count' => 0,
                'subtotal' => 0,
                'shipping' => 0,
                'total' => 0,
                'freeShippingRemaining' => self::FREE_SHIPPING_THRESHOLD,
            ];
        }

        $productIds = collect($rawCart)->pluck('product_id')->unique()->all();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $subtotal = 0;
        $count = 0;

        foreach ($rawCart as $key => $entry) {
            $product = $products->get($entry['product_id']);
            if (!$product) {
                continue;
            }
            $qty = max(1, (int) ($entry['quantity'] ?? 1));
            $lineTotal = $product->price * $qty;
            $subtotal += $lineTotal;
            $count += $qty;

            $items[] = [
                'key' => $key,
                'product' => $product,
                'size' => $entry['size'],
                'quantity' => $qty,
                'line_total' => $lineTotal,
            ];
        }

        $shipping = ($subtotal > 0 && $subtotal < self::FREE_SHIPPING_THRESHOLD)
            ? self::STANDARD_SHIPPING_FEE
            : 0;

        return [
            'items' => $items,
            'count' => $count,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
            'freeShippingRemaining' => max(0, self::FREE_SHIPPING_THRESHOLD - $subtotal),
        ];
    }

    /**
     * Persist current session bag into the SQLite carts & cart_items tables for backend visibility.
     *
     * @param array<string, array<string, mixed>> $rawCart
     */
    public function syncSessionCartToDatabase(array $rawCart): void
    {
        $token = session('rc_cart_token');
        if (!$token) {
            $token = 'bag-' . Str::random(24);
            session(['rc_cart_token' => $token]);
        }

        DB::transaction(function () use ($token, $rawCart) {
            $user = Auth::user();
            $cart = Cart::firstOrNew(['session_token' => $token]);

            if (empty($rawCart)) {
                if ($cart->exists) {
                    $cart->items()->delete();
                    $cart->update([
                        'item_count' => 0,
                        'subtotal' => 0,
                        'status' => 'abandoned',
                        'last_activity_at' => now(),
                    ]);
                }

                return;
            }

            $productIds = collect($rawCart)->pluck('product_id')->unique()->all();
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            $subtotal = 0;
            $itemCount = 0;
            $rows = [];

            foreach ($rawCart as $entry) {
                $product = $products->get($entry['product_id']);
                if (!$product) {
                    continue;
                }
                $qty = max(1, (int) ($entry['quantity'] ?? 1));
                $lineTotal = $product->price * $qty;
                $subtotal += $lineTotal;
                $itemCount += $qty;

                $rows[] = [
                    'product_id' => $product->id,
                    'size' => $entry['size'],
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                    'line_total' => $lineTotal,
                ];
            }

            $cart->fill([
                'user_id' => $user?->id ?? $cart->user_id,
                'customer_name' => $user?->name ?? $cart->customer_name ?? 'Guest Shopper',
                'customer_email' => $user?->email ?? $cart->customer_email,
                'status' => 'active',
                'item_count' => $itemCount,
                'subtotal' => $subtotal,
                'last_activity_at' => now(),
            ]);
            $cart->save();

            $cart->items()->delete();
            if (!empty($rows)) {
                $cart->items()->createMany($rows);
            }
        });
    }

    /**
     * Mark the current session cart as converted when checkout succeeds.
     */
    public function markSessionCartConverted(Order $order): void
    {
        $token = session('rc_cart_token');
        if ($token) {
            Cart::where('session_token', $token)->update([
                'status' => 'converted',
                'customer_name' => $order->customer_name,
                'customer_email' => $order->email,
                'user_id' => $order->user_id,
                'last_activity_at' => now(),
            ]);
            session()->forget('rc_cart_token');
        }
    }

    /**
     * Return product IDs saved in the session wishlist.
     *
     * @return list<int>
     */
    public function getWishlistIds(): array
    {
        return array_values(array_map('intval', session('rc_wishlist', [])));
    }
}

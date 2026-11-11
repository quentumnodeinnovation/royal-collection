<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\StorefrontService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly StorefrontService $storefront)
    {
    }

    /**
     * Show shopping bag and checkout page.
     */
    public function index(): View
    {
        return view('cart');
    }

    /**
     * Add an item to the session shopping bag and sync to carts table.
     */
    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'size' => ['required', 'string', 'max:20'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $qty = (int) ($validated['quantity'] ?? 1);
        $size = $validated['size'];
        $key = $product->id . '::' . $size;

        $cart = session('rc_cart', []);
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = min(10, $cart[$key]['quantity'] + $qty);
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'size' => $size,
                'quantity' => $qty,
            ];
        }

        session(['rc_cart' => $cart]);
        $this->storefront->syncSessionCartToDatabase($cart);
        $summary = $this->storefront->getCartSummary();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => "{$product->name} ({$size}) added to bag.",
                'cart' => $this->serializeCart($summary),
            ]);
        }

        return back()->with('success', "{$product->name} (Size {$size}) added to your Royal Collection bag.");
    }

    /**
     * Update line item quantity or remove if zero.
     */
    public function update(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:0', 'max:10'],
        ]);

        $cart = session('rc_cart', []);
        $key = $validated['key'];

        if (isset($cart[$key])) {
            if ((int) $validated['quantity'] <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = (int) $validated['quantity'];
            }
            session(['rc_cart' => $cart]);
            $this->storefront->syncSessionCartToDatabase($cart);
        }

        $summary = $this->storefront->getCartSummary();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'cart' => $this->serializeCart($summary),
            ]);
        }

        return back()->with('success', 'Bag updated.');
    }

    /**
     * Remove an item from the bag.
     */
    public function remove(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string'],
        ]);

        $cart = session('rc_cart', []);
        unset($cart[$validated['key']]);
        session(['rc_cart' => $cart]);
        $this->storefront->syncSessionCartToDatabase($cart);

        $summary = $this->storefront->getCartSummary();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'cart' => $this->serializeCart($summary),
            ]);
        }

        return back()->with('success', 'Item removed from bag.');
    }

    /**
     * Persist an order in the isolated Royal Collection SQLite database.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $summary = $this->storefront->getCartSummary();
        if (empty($summary['items'])) {
            return redirect()->route('shop')->with('error', 'Your bag is empty. Add items before checking out.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'min:8', 'max:20'],
            'address' => ['required', 'string', 'max:400'],
            'city' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'min:4', 'max:12'],
            'payment_method' => ['required', 'in:cod,upi,card'],
        ]);

        $order = DB::transaction(function () use ($validated, $summary) {
            $order = Order::create([
                'order_number' => 'RC-' . strtoupper(Str::random(7)),
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'pincode' => $validated['pincode'],
                'payment_method' => $validated['payment_method'],
                'subtotal' => $summary['subtotal'],
                'shipping' => $summary['shipping'],
                'total' => $summary['total'],
                'status' => 'confirmed',
            ]);

            foreach ($summary['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'size' => $item['size'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['product']->price,
                    'line_total' => $item['line_total'],
                ]);
            }

            return $order;
        });

        $this->storefront->markSessionCartConverted($order);
        session()->forget('rc_cart');

        return redirect()->route('order.success', $order->order_number);
    }

    /**
     * Render order confirmation view.
     */
    public function orderSuccess(string $orderNumber): View
    {
        $order = Order::with('items.product')->where('order_number', $orderNumber)->firstOrFail();

        return view('order-success', ['order' => $order]);
    }

    /**
     * Format cart payload cleanly for JSON responses used by the slide-over bag drawer.
     *
     * @param array<string, mixed> $summary
     * @return array<string, mixed>
     */
    private function serializeCart(array $summary): array
    {
        $items = array_map(fn ($item) => [
            'key' => $item['key'],
            'name' => $item['product']->name,
            'slug' => $item['product']->slug,
            'image' => asset($item['product']->image),
            'price' => $item['product']->price,
            'size' => $item['size'],
            'quantity' => $item['quantity'],
            'line_total' => $item['line_total'],
        ], $summary['items']);

        return [
            'items' => $items,
            'count' => $summary['count'],
            'subtotal' => $summary['subtotal'],
            'shipping' => $summary['shipping'],
            'total' => $summary['total'],
            'freeShippingRemaining' => $summary['freeShippingRemaining'],
        ];
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CartController extends Controller
{
    public const STATUSES = ['active', 'abandoned', 'converted'];

    public function index(Request $request): View
    {
        $statusFilter = $request->query('status');
        $search = trim((string) $request->query('q', ''));

        $carts = Cart::query()
            ->with(['user', 'items.product'])
            ->when($statusFilter && in_array($statusFilter, self::STATUSES, true), fn ($q) => $q->where('status', $statusFilter))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('session_token', 'like', "%{$search}%");
                });
            })
            ->latest('last_activity_at')
            ->paginate(15)
            ->withQueryString();

        $countsByStatus = Cart::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        $activeValue = (int) Cart::where('status', 'active')->sum('subtotal');
        $abandonedValue = (int) Cart::where('status', 'abandoned')->sum('subtotal');
        $statuses = self::STATUSES;

        return view('admin.carts.index', compact(
            'carts',
            'statusFilter',
            'search',
            'countsByStatus',
            'activeValue',
            'abandonedValue',
            'statuses'
        ));
    }

    public function show(Cart $cart): View
    {
        $cart->load(['user', 'items.product.category']);
        $statuses = self::STATUSES;

        return view('admin.carts.show', compact('cart', 'statuses'));
    }

    public function updateStatus(Request $request, Cart $cart): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
        ]);

        $cart->update(['status' => $data['status']]);

        return back()->with('success', 'Cart #'.$cart->id.' status updated to '.ucfirst($data['status']).'.');
    }

    public function destroy(Cart $cart): RedirectResponse
    {
        $cart->delete();

        return redirect()
            ->route('admin.carts.index')
            ->with('success', 'Cart record cleared successfully.');
    }
}

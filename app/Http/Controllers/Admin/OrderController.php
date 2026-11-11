<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public const STATUSES = ['confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];

    public function index(Request $request): View
    {
        $statusFilter = $request->query('status');
        $search = trim((string) $request->query('q', ''));

        $orders = Order::query()
            ->withCount('items')
            ->when($statusFilter && in_array($statusFilter, self::STATUSES, true), fn ($q) => $q->where('status', $statusFilter))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $countsByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        $totalCount = array_sum($countsByStatus);
        $statuses = self::STATUSES;

        return view('admin.orders.index', compact(
            'orders',
            'statusFilter',
            'search',
            'countsByStatus',
            'totalCount',
            'statuses'
        ));
    }

    public function show(Order $order): View
    {
        $order->load(['items.product', 'user']);
        $statuses = self::STATUSES;

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('success', "Order {$order->order_number} updated to ".ucfirst($data['status']).'.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $orderNumber = $order->order_number;
        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', "Order {$orderNumber} deleted.");
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalRevenue = (int) Order::where('status', '!=', 'cancelled')->sum('total');
        $totalOrders = Order::count();

        $statusBreakdown = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        $statuses = ['confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        $ordersByStatus = [];
        foreach ($statuses as $status) {
            $ordersByStatus[$status] = (int) ($statusBreakdown[$status] ?? 0);
        }

        $totalProducts = Product::count();
        $menProductsCount = Product::where('audience', 'men')->count();
        $kidsProductsCount = Product::where('audience', 'kids')->count();
        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(6)
            ->get();

        $activeCartsCount = Cart::where('status', 'active')->count();
        $abandonedCartsCount = Cart::where('status', 'abandoned')->count();
        $activeCartsValue = (int) Cart::where('status', 'active')->sum('subtotal');

        $totalUsers = User::count();
        $customerUsersCount = User::where('role', 'customer')->count();
        $adminUsersCount = User::where('role', 'admin')->count();

        $activeSlidersCount = Slider::where('is_active', true)->count();
        $totalSlidersCount = Slider::count();
        $totalCategoriesCount = Category::count();
        $openInquiriesCount = ContactMessage::where('status', '!=', 'resolved')->count();

        $recentOrders = Order::withCount('items')->latest()->take(6)->get();
        $recentCarts = Cart::with(['user', 'items'])->latest('last_activity_at')->take(5)->get();
        $recentInquiries = ContactMessage::latest()->take(4)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'ordersByStatus',
            'totalProducts',
            'menProductsCount',
            'kidsProductsCount',
            'lowStockProducts',
            'activeCartsCount',
            'abandonedCartsCount',
            'activeCartsValue',
            'totalUsers',
            'customerUsersCount',
            'adminUsersCount',
            'activeSlidersCount',
            'totalSlidersCount',
            'totalCategoriesCount',
            'openInquiriesCount',
            'recentOrders',
            'recentCarts',
            'recentInquiries'
        ));
    }
}

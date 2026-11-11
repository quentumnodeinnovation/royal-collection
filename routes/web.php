<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CartController as AdminCartController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SliderController as AdminSliderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Storefront Routes (Royal Collection — Mens & Kids Only)
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/men', [ShopController::class, 'index'])->defaults('audience', 'men')->name('men');
Route::get('/kids', [ShopController::class, 'index'])->defaults('audience', 'kids')->name('kids');
Route::get('/category/{categorySlug}', [ShopController::class, 'index'])->name('category.show');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product.show');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/order/{orderNumber}', [CartController::class, 'orderSuccess'])->name('order.success');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Customer Authentication & Account
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/account', [AuthController::class, 'account'])->name('account');
Route::post('/account', [AuthController::class, 'updateProfile'])->name('account.update');

// Admin Backend Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.attempt');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

        Route::get('/carts', [AdminCartController::class, 'index'])->name('carts.index');
        Route::get('/carts/{cart}', [AdminCartController::class, 'show'])->name('carts.show');
        Route::patch('/carts/{cart}/status', [AdminCartController::class, 'updateStatus'])->name('carts.status');
        Route::delete('/carts/{cart}', [AdminCartController::class, 'destroy'])->name('carts.destroy');

        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::patch('/products/{product}/toggle/{flag}', [AdminProductController::class, 'toggleFlag'])->name('products.toggle');

        Route::resource('categories', AdminCategoryController::class)->except(['show']);

        Route::resource('sliders', AdminSliderController::class)->except(['show']);
        Route::patch('/sliders/{slider}/toggle', [AdminSliderController::class, 'toggleActive'])->name('sliders.toggle');

        Route::resource('users', AdminUserController::class)->except(['show']);

        Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
        Route::patch('/inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])->name('inquiries.status');
        Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');
    });
});

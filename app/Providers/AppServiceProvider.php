<?php

namespace App\Providers;

use App\Services\StorefrontService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap shared storefront navigation, cart, and wishlist data for all views.
     */
    public function boot(StorefrontService $storefront): void
    {
        View::composer('*', function ($view) use ($storefront) {
            if (!Schema::hasTable('categories')) {
                return;
            }

            $drawerCategories = $storefront->getDrawerCategories();
            $cartSummary = $storefront->getCartSummary();
            $wishlistIds = $storefront->getWishlistIds();

            $view->with([
                'navMenCategories' => $drawerCategories['men'],
                'navKidsCategories' => $drawerCategories['kids'],
                'cartSummary' => $cartSummary,
                'wishlistIds' => $wishlistIds,
                'wishlistCount' => count($wishlistIds),
            ]);
        });
    }
}

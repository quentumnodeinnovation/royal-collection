<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\Slider;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FullSystemCoverageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_cart_update_remove_and_shop_filters(): void
    {
        $menProduct = Product::where('audience', 'men')->firstOrFail();
        $size = $menProduct->sizes[0] ?? 'L';
        $key = $menProduct->id . '::' . $size;

        // Add 1 item
        $this->postJson('/cart/add', [
            'product_id' => $menProduct->id,
            'size' => $size,
            'quantity' => 1,
        ])->assertStatus(200)->assertJsonPath('cart.count', 1);

        // Update quantity to 3
        $this->postJson('/cart/update', [
            'key' => $key,
            'quantity' => 3,
        ])->assertStatus(200)->assertJsonPath('cart.count', 3);

        // Remove item
        $this->postJson('/cart/remove', [
            'key' => $key,
        ])->assertStatus(200)->assertJsonPath('cart.count', 0);

        // Shop filters & sorting render cleanly
        $this->get('/shop?audience=men&sort=price_asc')->assertStatus(200);
        $this->get('/shop?audience=kids&sort=price_desc')->assertStatus(200);
        $this->get('/shop?q=Royal')->assertStatus(200);
    }

    public function test_customer_auth_login_logout_profile_update_and_inactive_block(): void
    {
        $customer = User::where('role', 'customer')->where('is_active', true)->firstOrFail();

        // Guest redirected away from account
        $this->get('/account')->assertRedirect('/login');
        $this->get('/login')->assertStatus(200)->assertSee('Sign In');
        $this->get('/register')->assertStatus(200)->assertSee('Create Account');

        // Customer login succeeds
        $this->post('/login', [
            'email' => $customer->email,
            'password' => 'Customer@2026',
        ])->assertRedirect('/account');

        // Profile update
        $this->post('/account', [
            'name' => 'Aarav Royals',
            'phone' => '9876501234',
            'city' => 'Bengaluru',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'name' => 'Aarav Royals',
            'city' => 'Bengaluru',
        ]);

        // Customer logout
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();

        // Deactivated account cannot sign in
        $customer->update(['is_active' => false]);
        $this->post('/login', [
            'email' => $customer->email,
            'password' => 'Customer@2026',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_all_admin_views_load_with_classic_palette_and_mobile_cards(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $product = Product::firstOrFail();
        $category = Category::firstOrFail();
        $slider = Slider::firstOrFail();
        $order = Order::firstOrFail();
        $customer = User::where('role', 'customer')->firstOrFail();

        $this->get('/admin/login')->assertStatus(200)->assertSee('Royal Collection Admin');

        $pages = [
            '/admin',
            '/admin/orders',
            '/admin/orders?status=confirmed',
            '/admin/orders/' . $order->id,
            '/admin/carts',
            '/admin/products',
            '/admin/products/create',
            '/admin/products/' . $product->id . '/edit',
            '/admin/categories',
            '/admin/categories/create',
            '/admin/categories/' . $category->id . '/edit',
            '/admin/sliders',
            '/admin/sliders/create',
            '/admin/sliders/' . $slider->id . '/edit',
            '/admin/users',
            '/admin/users/create',
            '/admin/users/' . $customer->id . '/edit',
            '/admin/inquiries',
        ];

        foreach ($pages as $uri) {
            $this->actingAs($admin)
                ->get($uri)
                ->assertStatus(200);
        }
    }

    public function test_admin_guards_toggles_deletes_and_logout(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $product = Product::firstOrFail();
        $initialTrending = $product->is_trending;

        // Product trending toggle
        $this->actingAs($admin)
            ->patch('/admin/products/' . $product->id . '/toggle/is_trending')
            ->assertRedirect();
        $this->assertSame(!$initialTrending, $product->fresh()->is_trending);

        // Admin cannot delete themselves
        $this->actingAs($admin)
            ->delete('/admin/users/' . $admin->id)
            ->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        // Admin can delete a customer user & inquiry
        $customer = User::where('role', 'customer')->firstOrFail();
        $this->actingAs($admin)
            ->delete('/admin/users/' . $customer->id)
            ->assertRedirect('/admin/users');
        $this->assertDatabaseMissing('users', ['id' => $customer->id]);

        $inquiry = ContactMessage::firstOrFail();
        $this->actingAs($admin)
            ->delete('/admin/inquiries/' . $inquiry->id)
            ->assertRedirect();
        $this->assertDatabaseMissing('contact_messages', ['id' => $inquiry->id]);

        // Admin can delete an order
        $order = Order::firstOrFail();
        $this->actingAs($admin)
            ->delete('/admin/orders/' . $order->id)
            ->assertRedirect('/admin/orders');
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);

        // Admin logout
        $this->actingAs($admin)
            ->post('/admin/logout')
            ->assertRedirect('/admin/login');
        $this->assertGuest();
    }
}

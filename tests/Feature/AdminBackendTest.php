<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\Slider;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBackendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_guest_is_redirected_and_customer_is_forbidden_from_admin(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/orders')->assertRedirect('/admin/login');

        $customer = User::where('role', 'customer')->firstOrFail();

        $this->actingAs($customer)
            ->get('/admin')
            ->assertStatus(403);

        // Non-admin cannot authenticate via admin login form
        $this->post('/logout');
        $this->post('/admin/login', [
            'email' => $customer->email,
            'password' => 'Customer@2026',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_sign_in_and_view_dashboard_kpis(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'Royal@2026',
        ])->assertRedirect('/admin');

        $this->get('/admin')
            ->assertStatus(200)
            ->assertSee('Dashboard Overview')
            ->assertSee('Total Revenue')
            ->assertSee('Live Carts')
            ->assertSee('Low Stock Monitor');
    }

    public function test_admin_can_manage_orders_and_update_status(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $order = Order::firstOrFail();

        $this->actingAs($admin)
            ->get('/admin/orders')
            ->assertStatus(200)
            ->assertSee($order->order_number);

        $this->actingAs($admin)
            ->get('/admin/orders/'.$order->id)
            ->assertStatus(200)
            ->assertSee($order->customer_name);

        $this->actingAs($admin)
            ->patch('/admin/orders/'.$order->id.'/status', ['status' => 'shipped'])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'shipped',
        ]);
    }

    public function test_storefront_bag_syncs_to_carts_table_and_admin_can_manage_carts(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $product = Product::firstOrFail();

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'size' => $product->sizes[0] ?? 'L',
            'quantity' => 2,
        ])->assertStatus(200);

        $syncedCart = Cart::latest('id')->firstOrFail();
        $this->assertSame(2, $syncedCart->item_count);
        $this->assertSame('active', $syncedCart->status);

        $this->actingAs($admin)
            ->get('/admin/carts')
            ->assertStatus(200)
            ->assertSee('Shopping Carts Monitor');

        $this->actingAs($admin)
            ->get('/admin/carts/'.$syncedCart->id)
            ->assertStatus(200)
            ->assertSee($product->name);

        $this->actingAs($admin)
            ->patch('/admin/carts/'.$syncedCart->id.'/status', ['status' => 'abandoned'])
            ->assertRedirect();

        $this->assertDatabaseHas('carts', [
            'id' => $syncedCart->id,
            'status' => 'abandoned',
        ]);

        $this->actingAs($admin)
            ->delete('/admin/carts/'.$syncedCart->id)
            ->assertRedirect('/admin/carts');

        $this->assertDatabaseMissing('carts', ['id' => $syncedCart->id]);
    }

    public function test_admin_can_crud_products_and_categories_within_mens_and_kids(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        // Create Category
        $this->actingAs($admin)
            ->post('/admin/categories', [
                'name' => 'Mens Club Jackets',
                'audience' => 'men',
                'group_type' => 'topwear',
                'tagline' => 'Tailored evening bombers',
                'sort_order' => 99,
                'is_featured' => '1',
                'image_path' => '/images/products/men-oversized-espresso.png',
            ])->assertRedirect('/admin/categories');

        $category = Category::where('name', 'Mens Club Jackets')->firstOrFail();
        $this->assertSame('men', $category->audience);

        // Create Product under new Category
        $this->actingAs($admin)
            ->post('/admin/products', [
                'category_id' => $category->id,
                'name' => 'Royal Bomber Jacket Onyx',
                'sku' => 'RC-MEN-JACKET-999',
                'subtitle' => 'Winter Layering',
                'fit_badge' => 'RELAXED FIT',
                'fabric_badge' => 'SUEDE BLEND',
                'price' => 3499,
                'mrp' => 4299,
                'sizes_input' => 'M, L, XL',
                'description' => 'Tailored royal suede bomber jacket for evenings.',
                'stock' => 14,
                'is_trending' => '1',
                'image_path' => '/images/products/men-oversized-espresso.png',
            ])->assertRedirect('/admin/products');

        $product = Product::where('sku', 'RC-MEN-JACKET-999')->firstOrFail();
        $this->assertSame(['M', 'L', 'XL'], $product->sizes);
        $this->assertSame('men', $product->audience);

        // Update Product
        $this->actingAs($admin)
            ->put('/admin/products/'.$product->id, [
                'category_id' => $category->id,
                'name' => 'Royal Bomber Jacket Onyx Edition',
                'sku' => 'RC-MEN-JACKET-999',
                'price' => 3299,
                'mrp' => 4299,
                'sizes_input' => 'M, L, XL',
                'description' => 'Updated tailored bomber jacket.',
                'stock' => 18,
            ])->assertRedirect('/admin/products');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Royal Bomber Jacket Onyx Edition',
            'price' => 3299,
        ]);

        // Category deletion blocked while product attached
        $this->actingAs($admin)
            ->delete('/admin/categories/'.$category->id)
            ->assertRedirect();
        $this->assertDatabaseHas('categories', ['id' => $category->id]);

        // Delete product then category succeeds
        $this->actingAs($admin)
            ->delete('/admin/products/'.$product->id)
            ->assertRedirect('/admin/products');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);

        $this->actingAs($admin)
            ->delete('/admin/categories/'.$category->id)
            ->assertRedirect('/admin/categories');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_slider_crud_reflects_live_on_homepage(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        $this->actingAs($admin)
            ->followingRedirects()
            ->post('/admin/sliders', [
                'eyebrow' => 'ROYAL COLLECTION · VIP DROP',
                'title' => 'Festive Sovereign Linen Drop 2026',
                'subtitle' => 'Crafted exclusively for Men & Kids.',
                'primary_cta_label' => 'Shop Drop',
                'primary_cta_url' => '/shop',
                'sort_order' => 1,
                'is_active' => '1',
                'image_path' => '/images/banners/hero-mens.png',
            ])->assertStatus(200);

        $slider = Slider::where('title', 'Festive Sovereign Linen Drop 2026')->firstOrFail();

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Festive Sovereign Linen Drop 2026');

        // Hide slider and confirm homepage no longer renders it
        $this->actingAs($admin)
            ->followingRedirects()
            ->patch('/admin/sliders/'.$slider->id.'/toggle')
            ->assertStatus(200);

        $this->assertFalse($slider->fresh()->is_active);

        $this->get('/')
            ->assertStatus(200)
            ->assertDontSee('Festive Sovereign Linen Drop 2026');
    }

    public function test_admin_can_manage_users_inquiries_and_customer_can_register_and_view_account(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();

        // Admin creates user
        $this->actingAs($admin)
            ->post('/admin/users', [
                'name' => 'Vikram Singh',
                'email' => 'vikram@example.com',
                'role' => 'customer',
                'phone' => '+91 9822211100',
                'city' => 'Jaipur',
                'is_active' => '1',
                'password' => 'Secret@123',
            ])->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', ['email' => 'vikram@example.com', 'city' => 'Jaipur']);

        // Inquiry status update
        $inquiry = ContactMessage::firstOrFail();
        $this->actingAs($admin)
            ->patch('/admin/inquiries/'.$inquiry->id.'/status', ['status' => 'resolved'])
            ->assertRedirect();
        $this->assertDatabaseHas('contact_messages', ['id' => $inquiry->id, 'status' => 'resolved']);

        // Customer registration & account page
        $this->post('/logout');
        $this->post('/register', [
            'name' => 'Nikhil Rao',
            'email' => 'nikhil.rao@example.com',
            'phone' => '9898989898',
            'city' => 'Pune',
            'password' => 'RoyalPass123',
            'password_confirmation' => 'RoyalPass123',
        ])->assertRedirect('/account');

        $this->get('/account')
            ->assertStatus(200)
            ->assertSee('Welcome, Nikhil Rao');
    }
}

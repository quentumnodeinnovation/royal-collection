<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_homepage_renders_royal_collection_with_mens_and_kids_only(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('ROYAL COLLECTION');
        $response->assertSee('royal-collection-logo.png');
        $response->assertSee('MENS COLLECTION');
        $response->assertSee('KIDS COLLECTION');

        // Confirm only Men & Kids categories exist in DB
        $audiences = Category::query()->distinct()->pluck('audience')->all();
        sort($audiences);
        $this->assertSame(['kids', 'men'], $audiences);
    }

    public function test_shop_men_kids_category_and_product_pages_load_correctly(): void
    {
        $this->get('/shop')->assertStatus(200)->assertSee('All Royal Collection Apparel');
        $this->get('/men')->assertStatus(200)->assertSee('Mens Luxury Apparel');
        $this->get('/kids')->assertStatus(200)->assertSee('Kids Luxury Apparel');

        $menCategory = Category::where('audience', 'men')->firstOrFail();
        $this->get('/category/' . $menCategory->slug)->assertStatus(200)->assertSee($menCategory->name);

        $searchRes = $this->get('/shop?q=Linen');
        $searchRes->assertStatus(200)->assertSee('Riviera Sky Pure Linen Resort Shirt');

        $product = Product::firstOrFail();
        $this->get('/product/' . $product->slug)
            ->assertStatus(200)
            ->assertSee($product->name);
    }

    public function test_contact_page_and_inquiry_submission_persist_to_sqlite(): void
    {
        $this->get('/contact')->assertStatus(200)->assertSee('Contact Us');

        $response = $this->post('/contact', [
            'name' => 'Rohan Verma',
            'email' => 'rohan@example.com',
            'phone' => '+91 9811122334',
            'subject' => 'Mens & Kids Sizing Help',
            'message' => 'Can you confirm chest measurements for size L in oversized tees?',
        ]);

        $response->assertRedirect('/contact');
        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Rohan Verma',
            'email' => 'rohan@example.com',
            'subject' => 'Mens & Kids Sizing Help',
        ]);
    }

    public function test_cart_wishlist_and_checkout_flow(): void
    {
        $product = Product::where('audience', 'men')->firstOrFail();

        // Wishlist toggle
        $wishRes = $this->postJson('/wishlist/toggle', ['product_id' => $product->id]);
        $wishRes->assertStatus(200)->assertJson(['ok' => true, 'saved' => true, 'count' => 1]);
        $this->get('/wishlist')->assertStatus(200)->assertSee($product->name);

        // Add to cart
        $cartRes = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'size' => 'L',
            'quantity' => 2,
        ]);
        $cartRes->assertStatus(200)->assertJsonPath('cart.count', 2);

        $this->get('/cart')->assertStatus(200)->assertSee($product->name);

        // Checkout
        $checkoutRes = $this->post('/checkout', [
            'customer_name' => 'Kabir Mehta',
            'email' => 'kabir@example.com',
            'phone' => '9876543210',
            'address' => '42 Carter Road, Bandra West',
            'city' => 'Mumbai',
            'pincode' => '400050',
            'payment_method' => 'cod',
        ]);

        $order = Order::latest('id')->firstOrFail();
        $checkoutRes->assertRedirect('/order/' . $order->order_number);
        $this->get('/order/' . $order->order_number)->assertStatus(200)->assertSee('Thank You, Kabir Mehta!');
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'size' => 'L',
            'quantity' => 2,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BackendSeeder extends Seeder
{
    /**
     * Seed default Admin/Customer users, dynamic Hero Sliders, sample Orders, Carts, and Inquiries.
     */
    public function run(): void
    {
        // 1. Hero Sliders
        if (Slider::count() === 0) {
            $sliders = [
                [
                    'eyebrow' => 'ROYAL COLLECTION · NEW SEASON',
                    'title' => 'LUXURY STREETWEAR FOR MEN & KIDS',
                    'subtitle' => 'Crafted in 260 GSM heavy-gauge combed cotton, breathable European linen, and kid-safe bio-wash fleece.',
                    'image' => '/images/banners/hero-mens.jpg',
                    'primary_cta_label' => 'Shop Mens',
                    'primary_cta_url' => '/men',
                    'secondary_cta_label' => 'Shop Kids',
                    'secondary_cta_url' => '/kids',
                    'sort_order' => 1,
                    'is_active' => true,
                ],
                [
                    'eyebrow' => 'ROYAL KIDS STUDIO · AGES 2Y–13Y',
                    'title' => 'PLAY-PROOF LUXURY FOR LITTLE MONARCHS',
                    'subtitle' => 'Ultra-soft denim sets, varsity hoodies, and breathable co-ord outfits engineered for nonstop comfort.',
                    'image' => '/images/banners/hero-kids.jpg',
                    'primary_cta_label' => 'Explore Kids Edit',
                    'primary_cta_url' => '/kids',
                    'secondary_cta_label' => 'Shop Full Catalog',
                    'secondary_cta_url' => '/shop',
                    'sort_order' => 2,
                    'is_active' => true,
                ],
                [
                    'eyebrow' => 'SIGNATURE SHIRTING & LINEN',
                    'title' => 'ELEVATED SHIRTS, CHINOS & KNIT POLOS',
                    'subtitle' => 'Precision-cut pure linens, Oxford weaves, and structured overshirts tailored for every occasion.',
                    'image' => '/images/banners/hero-shirts.jpg',
                    'primary_cta_label' => 'Explore Mens Shirts',
                    'primary_cta_url' => '/category/men-shirts',
                    'secondary_cta_label' => null,
                    'secondary_cta_url' => null,
                    'sort_order' => 3,
                    'is_active' => true,
                ],
            ];

            foreach ($sliders as $slide) {
                Slider::create($slide);
            }
        }

        // 2. Admin & Sample Customer Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@royalcollection.in'],
            [
                'name' => 'Royal Collection Admin',
                'role' => 'admin',
                'phone' => '+91 98200 11400',
                'city' => 'Mumbai',
                'is_active' => true,
                'password' => Hash::make('Royal@2026'),
            ]
        );

        $aarav = User::updateOrCreate(
            ['email' => 'aarav.sharma@example.com'],
            [
                'name' => 'Aarav Sharma',
                'role' => 'customer',
                'phone' => '+91 98111 44520',
                'city' => 'New Delhi',
                'is_active' => true,
                'password' => Hash::make('Customer@2026'),
            ]
        );

        $priya = User::updateOrCreate(
            ['email' => 'priya.nair@example.com'],
            [
                'name' => 'Priya Nair',
                'role' => 'customer',
                'phone' => '+91 98450 88210',
                'city' => 'Bengaluru',
                'is_active' => true,
                'password' => Hash::make('Customer@2026'),
            ]
        );

        $kabir = User::updateOrCreate(
            ['email' => 'kabir.mehta@example.com'],
            [
                'name' => 'Kabir Mehta',
                'role' => 'customer',
                'phone' => '+91 98765 43210',
                'city' => 'Mumbai',
                'is_active' => true,
                'password' => Hash::make('Customer@2026'),
            ]
        );

        // 3. Sample Orders across statuses
        if (Order::count() === 0) {
            $tee = Product::where('slug', 'royal-obsidian-heavyweight-oversized-tee')->first();
            $linen = Product::where('slug', 'riviera-sky-pure-linen-resort-shirt')->first();
            $kidsDenim = Product::where('slug', 'little-monarch-denim-jacket-mint-tee-set')->first();
            $chinos = Product::where('slug', 'tailored-sand-straight-cotton-chinos')->first();

            if ($tee && $linen && $kidsDenim && $chinos) {
                $o1 = Order::create([
                    'order_number' => 'RC-260101',
                    'user_id' => $aarav->id,
                    'customer_name' => $aarav->name,
                    'email' => $aarav->email,
                    'phone' => $aarav->phone,
                    'address' => '24 Golf Links, Lodhi Road',
                    'city' => 'New Delhi',
                    'pincode' => '110003',
                    'payment_method' => 'upi',
                    'subtotal' => $tee->price + $linen->price,
                    'shipping' => 0,
                    'total' => $tee->price + $linen->price,
                    'status' => 'delivered',
                ]);
                OrderItem::create([
                    'order_id' => $o1->id,
                    'product_id' => $tee->id,
                    'product_name' => $tee->name,
                    'size' => 'L',
                    'quantity' => 1,
                    'unit_price' => $tee->price,
                    'line_total' => $tee->price,
                ]);
                OrderItem::create([
                    'order_id' => $o1->id,
                    'product_id' => $linen->id,
                    'product_name' => $linen->name,
                    'size' => 'L',
                    'quantity' => 1,
                    'unit_price' => $linen->price,
                    'line_total' => $linen->price,
                ]);

                $o2 = Order::create([
                    'order_number' => 'RC-260102',
                    'user_id' => $priya->id,
                    'customer_name' => $priya->name,
                    'email' => $priya->email,
                    'phone' => $priya->phone,
                    'address' => '108 Indiranagar 100ft Road',
                    'city' => 'Bengaluru',
                    'pincode' => '560038',
                    'payment_method' => 'card',
                    'subtotal' => $kidsDenim->price,
                    'shipping' => 0,
                    'total' => $kidsDenim->price,
                    'status' => 'processing',
                ]);
                OrderItem::create([
                    'order_id' => $o2->id,
                    'product_id' => $kidsDenim->id,
                    'product_name' => $kidsDenim->name,
                    'size' => '6-7Y',
                    'quantity' => 1,
                    'unit_price' => $kidsDenim->price,
                    'line_total' => $kidsDenim->price,
                ]);

                $o3 = Order::create([
                    'order_number' => 'RC-260103',
                    'user_id' => $kabir->id,
                    'customer_name' => $kabir->name,
                    'email' => $kabir->email,
                    'phone' => $kabir->phone,
                    'address' => '42 Carter Road, Bandra West',
                    'city' => 'Mumbai',
                    'pincode' => '400050',
                    'payment_method' => 'cod',
                    'subtotal' => $chinos->price,
                    'shipping' => 0,
                    'total' => $chinos->price,
                    'status' => 'confirmed',
                ]);
                OrderItem::create([
                    'order_id' => $o3->id,
                    'product_id' => $chinos->id,
                    'product_name' => $chinos->name,
                    'size' => '32',
                    'quantity' => 1,
                    'unit_price' => $chinos->price,
                    'line_total' => $chinos->price,
                ]);
            }
        }

        // 4. Sample Active / Abandoned Carts
        if (Cart::count() === 0) {
            $hoodie = Product::where('slug', 'monarch-jet-black-heavy-fleece-hoodie')->first();
            $kidsCoord = Product::where('slug', 'junior-trail-olive-hoodie-jogger-coord-set')->first();

            if ($hoodie && $kidsCoord) {
                $c1 = Cart::create([
                    'session_token' => 'demo-active-bag-01',
                    'user_id' => $aarav->id,
                    'customer_name' => $aarav->name,
                    'customer_email' => $aarav->email,
                    'status' => 'active',
                    'item_count' => 1,
                    'subtotal' => $hoodie->price,
                    'last_activity_at' => now()->subMinutes(18),
                ]);
                $c1->items()->create([
                    'product_id' => $hoodie->id,
                    'size' => 'XL',
                    'quantity' => 1,
                    'unit_price' => $hoodie->price,
                    'line_total' => $hoodie->price,
                ]);

                $c2 = Cart::create([
                    'session_token' => 'demo-abandoned-bag-02',
                    'user_id' => $priya->id,
                    'customer_name' => $priya->name,
                    'customer_email' => $priya->email,
                    'status' => 'abandoned',
                    'item_count' => 2,
                    'subtotal' => $kidsCoord->price * 2,
                    'last_activity_at' => now()->subHours(5),
                ]);
                $c2->items()->create([
                    'product_id' => $kidsCoord->id,
                    'size' => '8-9Y',
                    'quantity' => 2,
                    'unit_price' => $kidsCoord->price,
                    'line_total' => $kidsCoord->price * 2,
                ]);
            }
        }

        // 5. Sample Contact Inquiry
        if (ContactMessage::count() === 0) {
            ContactMessage::create([
                'name' => 'Vikram Patel',
                'email' => 'vikram.patel@example.com',
                'phone' => '+91 98250 11902',
                'subject' => 'Bulk / Studio Inquiry',
                'order_ref' => 'RC-260101',
                'message' => 'Looking to order 25 customized Royal Collection oversized t-shirts for an upcoming event.',
                'status' => 'new',
            ]);
        }
    }
}

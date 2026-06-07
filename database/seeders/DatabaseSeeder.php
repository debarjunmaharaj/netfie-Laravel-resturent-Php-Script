<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. --- SEED STAFF ACCOUNTS ---
        $staff = [
            [
                'name' => 'Alexander Admin',
                'email' => 'admin@cafeflow.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Charlie Cashier',
                'email' => 'cashier@cafeflow.com',
                'password' => Hash::make('password'),
                'role' => 'cashier',
            ],
            [
                'name' => 'Keanu Kitchen',
                'email' => 'kitchen@cafeflow.com',
                'password' => Hash::make('password'),
                'role' => 'kitchen',
            ],
            [
                'name' => 'Wendy Waiter',
                'email' => 'waiter@cafeflow.com',
                'password' => Hash::make('password'),
                'role' => 'waiter',
            ],
        ];

        foreach ($staff as $s) {
            User::create($s);
        }

        // 2. --- SEED MEMBER CUSTOMERS ---
        $customers = [
            [
                'name' => 'Alice Smith',
                'email' => 'alice@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ],
            [
                'name' => 'Bob Miller',
                'email' => 'bob@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ],
        ];

        $customerModels = [];
        foreach ($customers as $c) {
            $customerModels[] = User::create($c);
        }

        // 3. --- SEED CATEGORIES ---
        $categoriesData = [
            ['name' => 'Specialty Coffee', 'slug' => 'specialty-coffee', 'icon' => 'mug-hot'],
            ['name' => 'Artisanal Bakery', 'slug' => 'artisanal-bakery', 'icon' => 'bread-slice'],
            ['name' => 'Gourmet Desserts', 'slug' => 'gourmet-desserts', 'icon' => 'cake-candles'],
            ['name' => 'Ice Beverages', 'slug' => 'ice-beverages', 'icon' => 'glass-water'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[] = Category::create($cat);
        }

        // 4. --- SEED PRODUCTS (Unsplash URLs) ---
        $products = [
            // Specialty Coffee
            [
                'category_id' => $categories[0]->id,
                'name' => 'Single-Origin Espresso',
                'price' => 3.50,
                'image' => 'https://images.unsplash.com/photo-1510707513156-4b8d60924d03?auto=format&fit=crop&q=80&w=400',
                'description' => 'Double shot of our hand-roasted Colombian micromill bean. Notes of citrus and brown sugar.',
            ],
            [
                'category_id' => $categories[0]->id,
                'name' => 'Artisan Cappuccino',
                'price' => 4.50,
                'image' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?auto=format&fit=crop&q=80&w=400',
                'description' => 'Equal parts espresso, steamed organic milk, and dense microfoam with chocolate dust.',
            ],
            [
                'category_id' => $categories[0]->id,
                'name' => 'Salted Caramel Latte',
                'price' => 5.25,
                'image' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&q=80&w=400',
                'description' => 'Espresso with house-made salted caramel syrup, steamed milk, and a caramel drizzle.',
            ],
            [
                'category_id' => $categories[0]->id,
                'name' => 'Cold Brew Nitro',
                'price' => 4.75,
                'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&q=80&w=400',
                'description' => '18-hour slow steeped cold brew infused with nitrogen for an ultra-creamy stout-like pour.',
            ],

            // Artisanal Bakery
            [
                'category_id' => $categories[1]->id,
                'name' => 'Flaky Almond Croissant',
                'price' => 3.95,
                'image' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&q=80&w=400',
                'description' => 'Classic French butter croissant, loaded with rich frangipane paste and toasted flaked almonds.',
            ],
            [
                'category_id' => $categories[1]->id,
                'name' => 'Avocado Sourdough Toast',
                'price' => 9.50,
                'image' => 'https://images.unsplash.com/photo-1541532713592-79a0317b6b77?auto=format&fit=crop&q=80&w=400',
                'description' => 'Mashed organic avocados, cherry tomatoes, microgreens, and pumpkin seeds on toasted country sourdough.',
            ],
            [
                'category_id' => $categories[1]->id,
                'name' => 'Cinnamon Bun Glaze',
                'price' => 3.50,
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&q=80&w=400',
                'description' => 'Warm, cinnamon rolled soft dough generously smeared with pure vanilla cream cheese frosting.',
            ],

            // Gourmet Desserts
            [
                'category_id' => $categories[2]->id,
                'name' => 'Classic Espresso Tiramisu',
                'price' => 5.50,
                'image' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&q=80&w=400',
                'description' => 'Ladyfingers soaked in our signature espresso, layered with whipped mascarpone cheese and cocoa powder.',
            ],
            [
                'category_id' => $categories[2]->id,
                'name' => 'Matcha White Cheesecake',
                'price' => 5.95,
                'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?auto=format&fit=crop&q=80&w=400',
                'description' => 'Creamy Japanese Uji matcha cheesecake on a buttery Graham cracker base.',
            ],

            // Ice Beverages
            [
                'category_id' => $categories[3]->id,
                'name' => 'Iced Strawberry Matcha',
                'price' => 5.50,
                'image' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&q=80&w=400',
                'description' => 'Pure organic ceremonial matcha green tea whisked over cold milk and sweet strawberry purée.',
            ],
            [
                'category_id' => $categories[3]->id,
                'name' => 'Mango Sunshine Smoothie',
                'price' => 6.00,
                'image' => 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?auto=format&fit=crop&q=80&w=400',
                'description' => 'Fresh mango slices blended with Greek yogurt, bananas, and a splash of organic honey.',
            ],
        ];

        $productModels = [];
        foreach ($products as $p) {
            $productModels[] = Product::create($p);
        }

        // 5. --- SEED 7-DAY TRANSACTION SALES HISTORY ---
        $paymentMethods = ['Cash', 'Card', 'Mobile'];
        
        for ($i = 6; $i >= 1; $i--) {
            $date = now()->subDays($i);
            
            // Generate 3 to 6 orders per historical day
            $ordersCount = rand(3, 6);
            for ($o = 0; $o < $ordersCount; $o++) {
                $user = $customerModels[rand(0, 1)];
                
                // Select 1 to 3 random items
                $itemsCount = rand(1, 3);
                $total = 0;
                $orderItems = [];
                
                for ($it = 0; $it < $itemsCount; $it++) {
                    $prod = $productModels[rand(0, count($productModels) - 1)];
                    $qty = rand(1, 2);
                    $total += $prod->price * $qty;
                    $orderItems[] = [
                        'product_id' => $prod->id,
                        'quantity' => $qty,
                        'price' => $prod->price,
                    ];
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'customer_name' => $user->name,
                    'customer_phone' => '+1 555-01' . rand(10, 99),
                    'type' => rand(0, 1) ? 'POS' : 'Online',
                    'status' => 'Completed',
                    'total' => $total,
                    'payment_method' => $paymentMethods[rand(0, 2)],
                    'payment_status' => 'Paid',
                    'created_at' => $date->copy()->addHours(rand(8, 18))->addMinutes(rand(0, 59)),
                ]);

                foreach ($orderItems as $oi) {
                    $order->items()->create($oi);
                }
            }
        }

        // 6. --- SEED LIVE KITCHEN DESK TICKETS (TODAY) ---
        // Ticket 1: Pending (placed 5 mins ago)
        $order1 = Order::create([
            'user_id' => $customerModels[0]->id,
            'customer_name' => $customerModels[0]->name,
            'customer_phone' => '+1 555-0123',
            'type' => 'Online',
            'status' => 'Pending',
            'total' => $productModels[2]->price + $productModels[4]->price, // Latte + Almond Croissant
            'payment_method' => 'Mobile',
            'payment_status' => 'Paid',
            'notes' => 'Latte with extra oatmilk, please serve hot.',
            'created_at' => now()->subMinutes(5),
        ]);
        $order1->items()->create(['product_id' => $productModels[2]->id, 'quantity' => 1, 'price' => $productModels[2]->price]);
        $order1->items()->create(['product_id' => $productModels[4]->id, 'quantity' => 1, 'price' => $productModels[4]->price]);

        // Ticket 2: Preparing (placed 16 mins ago - marked late)
        $order2 = Order::create([
            'user_id' => null,
            'customer_name' => 'Walk-in Customer (Table 8)',
            'customer_phone' => '',
            'type' => 'POS',
            'status' => 'Preparing',
            'total' => ($productModels[1]->price * 2) + $productModels[5]->price, // Cappuccino x2 + Avocado Toast
            'payment_method' => 'Card',
            'payment_status' => 'Paid',
            'notes' => 'Avocado Toast with extra olive oil.',
            'created_at' => now()->subMinutes(16),
        ]);
        $order2->items()->create(['product_id' => $productModels[1]->id, 'quantity' => 2, 'price' => $productModels[1]->price]);
        $order2->items()->create(['product_id' => $productModels[5]->id, 'quantity' => 1, 'price' => $productModels[5]->price]);

        // Ticket 3: Ready (placed 22 mins ago)
        $order3 = Order::create([
            'user_id' => $customerModels[1]->id,
            'customer_name' => $customerModels[1]->name,
            'customer_phone' => '+1 555-9876',
            'type' => 'Online',
            'status' => 'Ready',
            'total' => $productModels[3]->price + $productModels[7]->price, // Nitro Brew + Tiramisu
            'payment_method' => 'Cash',
            'payment_status' => 'Unpaid',
            'created_at' => now()->subMinutes(22),
        ]);
        $order3->items()->create(['product_id' => $productModels[3]->id, 'quantity' => 1, 'price' => $productModels[3]->price]);
        $order3->items()->create(['product_id' => $productModels[7]->id, 'quantity' => 1, 'price' => $productModels[7]->price]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CafeController extends Controller
{
    // --- AUTHENTICATION ---

    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isStaff() ? redirect()->route('dashboard') : redirect('/');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            return $user->isStaff() ? redirect()->route('dashboard') : redirect('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);
        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }


    // --- PUBLIC FRONTEND (CUSTOMER WEBSITE) ---

    public function home(Request $request)
    {
        $categories = Category::with('products')->get();
        $products = Product::where('available', true)->get();
        
        // Track order if user has any active online order
        $activeOrder = null;
        if (Auth::check()) {
            $activeOrder = Order::where('user_id', Auth::id())
                ->where('type', 'Online')
                ->latest()
                ->first();
        }

        return view('home', compact('categories', 'products', 'activeOrder'));
    }

    public function placeOnlineOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'cart_items' => 'required|json',
            'notes' => 'nullable|string',
            'payment_method' => 'required|string',
        ]);

        $cart = json_decode($request->cart_items, true);
        if (empty($cart)) {
            return back()->with('error', 'Your ordering cart is empty.');
        }

        // Calculate total
        $total = 0;
        $itemsToCreate = [];

        foreach ($cart as $itemId => $item) {
            $product = Product::find($itemId);
            if (!$product || !$product->available) {
                return back()->with('error', 'One or more items in your cart are no longer available.');
            }
            $total += $product->price * $item['qty'];
            $itemsToCreate[] = [
                'product_id' => $product->id,
                'quantity' => $item['qty'],
                'price' => $product->price,
            ];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'type' => 'Online',
            'status' => 'Pending',
            'total' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => 'Unpaid',
            'notes' => $request->notes,
        ]);

        foreach ($itemsToCreate as $item) {
            $order->items()->create($item);
        }

        return redirect()->route('home')->with('success', 'Order placed successfully! Order ID: #' . $order->id);
    }


    // --- BACKEND DASHBOARD ---

    public function dashboard()
    {
        if (!Auth::user()->isStaff()) {
            return redirect('/login')->withErrors(['email' => 'Access denied. Staff only area.']);
        }

        $today = now()->startOfDay();
        
        // Key Statistics
        $todaySales = Order::where('payment_status', 'Paid')
            ->where('created_at', '>=', $today)
            ->sum('total');
            
        $todayOrders = Order::where('created_at', '>=', $today)->count();
        $pendingOrders = Order::where('status', 'Pending')->count();
        $preparingOrders = Order::where('status', 'Preparing')->count();
        $completedOrders = Order::where('status', 'Completed')->count();

        // Recent Orders
        $recentOrders = Order::with('items.product')->latest()->limit(8)->get();

        // Stats for categories sales
        $categoriesCount = Category::count();
        $productsCount = Product::count();

        // Simple weekly graph values
        $salesData = [];
        $daysLabel = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $daysLabel[] = $date->format('D');
            $salesData[] = Order::where('payment_status', 'Paid')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total');
        }

        return view('dashboard', compact(
            'todaySales',
            'todayOrders',
            'pendingOrders',
            'preparingOrders',
            'completedOrders',
            'recentOrders',
            'categoriesCount',
            'productsCount',
            'salesData',
            'daysLabel'
        ));
    }


    // --- POS TERMINAL ---

    public function pos()
    {
        if (!Auth::user()->isStaff()) {
            return redirect('/login')->withErrors(['email' => 'Access denied. Staff only area.']);
        }

        $categories = Category::with('products')->get();
        $products = Product::where('available', true)->get();
        
        // Fast customers list
        $customers = User::where('role', 'customer')->get();

        return view('pos', compact('categories', 'products', 'customers'));
    }

    public function placePosOrder(Request $request)
    {
        if (!Auth::user()->isStaff()) {
            return response()->json(['success' => false, 'message' => 'Access denied. Staff only.'], 403);
        }

        $request->validate([
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            'cart_items' => 'required|json',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $cart = json_decode($request->cart_items, true);
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty.'], 400);
        }

        $total = 0;
        $itemsToCreate = [];

        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
            }
            $total += $product->price * $item['qty'];
            $itemsToCreate[] = [
                'product_id' => $product->id,
                'quantity' => $item['qty'],
                'price' => $product->price,
            ];
        }

        $order = Order::create([
            'user_id' => Auth::id(), // cashier who logged it
            'customer_name' => $request->customer_name ?: 'Walk-in Customer',
            'customer_phone' => $request->customer_phone,
            'type' => 'POS',
            'status' => 'Pending',
            'total' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status ?: 'Paid',
            'notes' => $request->notes,
        ]);

        foreach ($itemsToCreate as $item) {
            $order->items()->create($item);
        }

        return response()->json([
            'success' => true,
            'message' => 'POS Order placed successfully!',
            'order_id' => $order->id,
            'total' => number_format($total, 2)
        ]);
    }


    // --- KITCHEN & ORDER LIST ---

    public function orders(Request $request)
    {
        if (!Auth::user()->isStaff()) {
            return redirect('/login')->withErrors(['email' => 'Access denied. Staff only area.']);
        }

        $query = Order::with('items.product')->latest();
        
        // Filter by status if specified
        if ($request->has('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }
        
        $orders = $query->paginate(20);

        return view('orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        if (!Auth::user()->isStaff()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Access denied. Staff only.'], 403);
            }
            return redirect('/login')->withErrors(['email' => 'Access denied. Staff only area.']);
        }

        $request->validate([
            'status' => 'required|string|in:Pending,Preparing,Ready,Completed,Cancelled',
            'payment_status' => 'nullable|string|in:Paid,Unpaid',
        ]);

        $order->status = $request->status;
        if ($request->has('payment_status')) {
            $order->payment_status = $request->payment_status;
        }
        
        // If completed, automatically mark as paid
        if ($request->status === 'Completed') {
            $order->payment_status = 'Paid';
        }

        $order->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Order updated.', 'order' => $order]);
        }

        return back()->with('success', 'Order #' . $order->id . ' updated successfully!');
    }


    // --- MENU / PRODUCT / CATEGORY MANAGEMENT ---

    public function menu()
    {
        if (!Auth::user()->isStaff()) {
            return redirect('/login')->withErrors(['email' => 'Access denied. Staff only area.']);
        }

        $categories = Category::with('products')->get();
        return view('menu', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        if (!Auth::user()->isStaff()) {
            return redirect('/login')->withErrors(['email' => 'Access denied. Staff only area.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?: 'coffee',
        ]);

        return back()->with('success', 'Category created successfully!');
    }

    public function updateCategory(Request $request, Category $category)
    {
        if (!Auth::user()->isStaff()) {
            return redirect('/login')->withErrors(['email' => 'Access denied. Staff only area.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?: $category->icon,
        ]);

        return back()->with('success', 'Category updated successfully!');
    }

    public function deleteCategory(Category $category)
    {
        if (!Auth::user()->isStaff()) {
            return redirect('/login')->withErrors(['email' => 'Access denied. Staff only area.']);
        }

        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }

    public function storeProduct(Request $request)
    {
        if (!Auth::user()->isStaff()) {
            return redirect('/login')->withErrors(['email' => 'Access denied. Staff only area.']);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|url', // Standardized to URL for ease of seeding/images
            'description' => 'nullable|string',
        ]);

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'image' => $request->image ?: 'https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&q=80&w=600',
            'description' => $request->description,
            'available' => true,
        ]);

        return back()->with('success', 'Product created successfully!');
    }

    public function updateProduct(Request $request, Product $product)
    {
        if (!Auth::user()->isStaff()) {
            return redirect('/login')->withErrors(['email' => 'Access denied. Staff only area.']);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|url',
            'description' => 'nullable|string',
            'available' => 'nullable|boolean',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'image' => $request->image ?: $product->image,
            'description' => $request->description,
            'available' => $request->has('available') ? (bool)$request->available : $product->available,
        ]);

        return back()->with('success', 'Product updated successfully!');
    }

    public function deleteProduct(Product $product)
    {
        if (!Auth::user()->isStaff()) {
            return redirect('/login')->withErrors(['email' => 'Access denied. Staff only area.']);
        }

        $product->delete();
        return back()->with('success', 'Product deleted successfully!');
    }
}

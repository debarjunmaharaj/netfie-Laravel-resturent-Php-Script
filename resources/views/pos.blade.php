@extends('layout')

@section('title', 'CaféFlow - POS Terminal')

@section('content')
<div class="h-screen flex flex-col bg-slate-100 dark:bg-dark-950 transition-colors duration-300" 
     x-data="posTerminalHandler()">

    <!-- TOP HEADER -->
    <header class="bg-white dark:bg-dark-900 border-b border-slate-200 dark:border-slate-800 px-6 py-3 flex items-center justify-between shrink-0 transition-colors">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="h-9 w-9 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-amber-100 dark:hover:bg-amber-950 text-slate-500 hover:text-amber-700 flex items-center justify-center transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-amber-600 flex items-center justify-center text-white text-sm shadow">
                    <i class="fa-solid fa-cash-register"></i>
                </div>
                <h1 class="text-lg font-bold tracking-tight">POS Terminal</h1>
            </div>
            <!-- Live Time Clock -->
            <div class="hidden sm:flex items-center gap-2 text-xs font-bold text-slate-400 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 px-3 py-1.5 rounded-xl">
                <i class="fa-regular fa-clock text-amber-500"></i>
                <span x-text="timeString">12:00 PM</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <!-- Theme Toggle -->
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 transition">
                <i x-cloak x-show="!darkMode" class="fa-solid fa-moon text-slate-600"></i>
                <i x-cloak x-show="darkMode" class="fa-solid fa-sun text-amber-400"></i>
            </button>

            <!-- Parked Orders Quick Dropdown Button -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400 font-bold rounded-xl text-xs flex items-center gap-2 transition">
                    <i class="fa-solid fa-parking"></i> Parked Carts 
                    <span class="bg-indigo-600 text-white rounded-full text-[9px] w-4.5 h-4.5 flex items-center justify-center" x-text="parkedCarts.length">0</span>
                </button>
                <div x-cloak x-show="open" @click.away="open = false" 
                     class="absolute right-0 mt-2 w-72 bg-white dark:bg-dark-900 border border-slate-150 dark:border-slate-850 rounded-2xl shadow-xl p-3 z-50">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400 mb-2 px-2">Parked Orders</h4>
                    <div class="max-h-56 overflow-y-auto space-y-2">
                        <template x-if="parkedCarts.length === 0">
                            <p class="text-[10px] text-slate-400 text-center py-4 italic">No parked carts.</p>
                        </template>
                        <template x-for="(pCart, index) in parkedCarts" :key="index">
                            <div class="flex items-center justify-between p-2 bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-xl">
                                <div class="min-w-0">
                                    <h5 class="text-xs font-bold truncate" x-text="pCart.name"></h5>
                                    <p class="text-[9px] text-slate-400" x-text="pCart.itemsCount + ' items • $' + pCart.total.toFixed(2)"></p>
                                </div>
                                <div class="flex gap-1.5">
                                    <button @click="recallCart(index); open = false" class="px-2 py-1 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-bold rounded-md transition">Recall</button>
                                    <button @click="deleteParkedCart(index)" class="text-rose-500 hover:text-rose-700 p-1"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Profile Tag -->
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-sm uppercase">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <div class="hidden md:block text-left">
                    <h4 class="font-bold text-xs leading-none">{{ Auth::user()->name }}</h4>
                    <span class="text-[9px] font-extrabold text-amber-500 uppercase">{{ Auth::user()->role }}</span>
                </div>
            </div>
        </div>
    </header>

    <!-- TERMINAL VIEWPORT WRAPPER -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- LEFT COLUMN: CATEGORIES, SEARCH, PRODUCT GRID -->
        <main class="flex-1 flex flex-col p-6 overflow-y-auto space-y-6">
            
            <!-- Filter Actions (Search Bar & Fast Categories) -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center shrink-0">
                <!-- Search bar -->
                <div class="md:col-span-4 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" x-model="searchQuery"
                           class="w-full pl-11 pr-4 py-2.5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 focus:border-amber-500 focus:ring-0 text-sm outline-none transition" 
                           placeholder="Search product name...">
                </div>

                <!-- Horizontal categories scroll bar -->
                <div class="md:col-span-8 flex gap-2 overflow-x-auto pb-1">
                    <button @click="activeCat = 'all'"
                            :class="activeCat === 'all' ? 'bg-amber-600 text-white shadow-md shadow-amber-600/30' : 'bg-white hover:bg-slate-50 text-slate-600 dark:bg-dark-900 dark:text-slate-300 dark:hover:bg-slate-800'"
                            class="px-4 py-2 rounded-xl font-semibold transition text-xs flex items-center gap-1.5 shrink-0">
                        <i class="fa-solid fa-list-check"></i> All Items
                    </button>
                    @foreach($categories as $category)
                    <button @click="activeCat = 'cat-{{ $category->id }}'"
                            :class="activeCat === 'cat-{{ $category->id }}' ? 'bg-amber-600 text-white shadow-md shadow-amber-600/30' : 'bg-white hover:bg-slate-50 text-slate-600 dark:bg-dark-900 dark:text-slate-300 dark:hover:bg-slate-800'"
                            class="px-4 py-2 rounded-xl font-semibold transition text-xs flex items-center gap-1.5 shrink-0">
                        <i class="fa-solid fa-{{ $category->icon ?: 'coffee' }}"></i> {{ $category->name }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Touch-optimized large buttons Product Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 flex-1">
                @foreach($products as $product)
                <!-- JavaScript filtering inside Alpine x-show -->
                <div x-show="(activeCat === 'all' || activeCat === 'cat-{{ $product->category_id }}') && 
                             ('{{ addslashes(strtolower($product->name)) }}'.includes(searchQuery.toLowerCase()))"
                     @click="addProduct({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->image }}')"
                     class="group bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-850 p-3 rounded-2xl shadow-sm hover:shadow-md cursor-pointer active:scale-97 select-none transition flex flex-col justify-between h-44">
                    
                    <div class="space-y-2">
                        <div class="h-20 w-full rounded-xl overflow-hidden relative">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                            <!-- Float Price Tag -->
                            <span class="absolute bottom-2 right-2 bg-slate-900/90 backdrop-blur-md px-2 py-0.5 rounded text-[10px] font-black text-amber-400 font-serif">
                                ${{ number_format($product->price, 2) }}
                            </span>
                        </div>
                        <h4 class="font-bold text-xs truncate text-slate-800 dark:text-white leading-tight group-hover:text-amber-600 transition">{{ $product->name }}</h4>
                    </div>

                    <div class="flex justify-between items-center text-[10px] font-bold text-slate-400 uppercase pt-2 border-t border-slate-50 dark:border-slate-800">
                        <span>{{ $product->category->name }}</span>
                        <i class="fa-solid fa-plus text-amber-500 text-xs"></i>
                    </div>
                </div>
                @endforeach
            </div>
        </main>

        <!-- RIGHT PANEL: INTERACTIVE ORDER CART DRAWER -->
        <aside class="w-96 bg-white dark:bg-dark-900 border-l border-slate-200 dark:border-slate-800 shrink-0 flex flex-col justify-between transition-colors duration-300">
            <!-- Cart Header -->
            <div class="p-4 border-b border-slate-150 dark:border-slate-850 flex items-center justify-between shrink-0">
                <h3 class="font-bold text-sm flex items-center gap-2"><i class="fa-solid fa-basket-shopping text-amber-600"></i> Active Cart</h3>
                <button @click="clearCart()" class="text-[10px] font-bold uppercase tracking-wider text-rose-500 hover:text-rose-700 transition">Clear Cart</button>
            </div>

            <!-- Customer Picker & Details -->
            <div class="p-4 bg-slate-50/50 dark:bg-slate-800/10 border-b border-slate-150 dark:border-slate-850 shrink-0 space-y-3">
                <div class="flex gap-2">
                    <div class="flex-1 min-w-0">
                        <label class="block text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-1">Customer Selection</label>
                        <select x-model="selectedCustomer" @change="pickCustomer($el.value)"
                                class="w-full px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 focus:ring-0 text-xs outline-none transition">
                            <option value="Walk-in">Walk-in Customer</option>
                            @foreach($customers as $c)
                            <option value="{{ $c->name }}|{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <input type="text" x-model="customerName" placeholder="Name"
                               class="w-full px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 focus:ring-0 text-xs outline-none transition">
                    </div>
                    <div>
                        <input type="text" x-model="customerPhone" placeholder="Phone"
                               class="w-full px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 focus:ring-0 text-xs outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Cart Items List Area -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                <template x-if="cart.length === 0">
                    <div class="text-center py-20 space-y-3">
                        <i class="fa-solid fa-mug-saucer text-4xl text-slate-200 dark:text-slate-800 animate-pulse"></i>
                        <p class="text-xs text-slate-400 font-semibold italic">Cart is currently empty.<br>Tap products to insert.</p>
                    </div>
                </template>

                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex items-center gap-3 p-2 bg-slate-50 dark:bg-slate-850/60 border border-slate-100 dark:border-slate-800/80 rounded-xl">
                        <div class="h-10 w-10 rounded-lg overflow-hidden shrink-0">
                            <img :src="item.image" :alt="item.name" class="h-full w-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-xs text-slate-900 dark:text-white truncate" x-text="item.name"></h4>
                            <p class="text-[10px] text-amber-700 dark:text-amber-400 font-bold" x-text="'$' + (item.price * item.qty).toFixed(2)"></p>
                        </div>
                        <!-- Adjust Qty -->
                        <div class="flex items-center gap-1.5">
                            <button @click="updateQty(index, -1)" class="w-5 h-5 bg-white dark:bg-dark-900 border border-slate-250 dark:border-slate-700 text-slate-500 rounded-md flex items-center justify-center hover:bg-slate-100 transition text-[9px]"><i class="fa-solid fa-minus"></i></button>
                            <span class="text-xs font-bold w-4 text-center" x-text="item.qty"></span>
                            <button @click="updateQty(index, 1)" class="w-5 h-5 bg-white dark:bg-dark-900 border border-slate-250 dark:border-slate-700 text-slate-500 rounded-md flex items-center justify-center hover:bg-slate-100 transition text-[9px]"><i class="fa-solid fa-plus"></i></button>
                        </div>
                        <button @click="removeProduct(index)" class="text-slate-400 hover:text-rose-600 transition p-1"><i class="fa-solid fa-trash-can text-xs"></i></button>
                    </div>
                </template>
            </div>

            <!-- Cart Footer (Totals, Payments & checkout trigger) -->
            <div class="p-4 border-t border-slate-150 dark:border-slate-850 bg-slate-50/50 dark:bg-slate-900/50 shrink-0 space-y-4">
                
                <!-- Notes textarea -->
                <div>
                    <input type="text" x-model="notes" placeholder="Order notes (e.g. Table 5, no ice)"
                           class="w-full px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 focus:ring-0 text-[10px] outline-none transition">
                </div>

                <!-- Calculation List -->
                <div class="space-y-1.5 border-t border-b border-slate-200/50 dark:border-slate-800/50 py-2 text-xs">
                    <div class="flex justify-between items-center text-slate-400 font-medium">
                        <span>Items Subtotal</span>
                        <span class="font-bold text-slate-700 dark:text-slate-300" x-text="'$' + getSubtotal().toFixed(2)">$0.00</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-400 font-medium">
                        <span>Sales Tax (5%)</span>
                        <span class="font-bold text-slate-700 dark:text-slate-300" x-text="'$' + getTax().toFixed(2)">$0.00</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-400 font-medium pt-1 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-sm font-bold text-slate-900 dark:text-white">Grand Total</span>
                        <span class="text-lg font-serif font-black text-amber-700 dark:text-amber-400" x-text="'$' + getGrandTotal().toFixed(2)">$0.00</span>
                    </div>
                </div>

                <!-- Payment Selection tabs -->
                <div class="space-y-2">
                    <label class="block text-[9px] font-bold uppercase tracking-wider text-slate-400">Payment Selection</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button @click="paymentMethod = 'Cash'"
                                :class="paymentMethod === 'Cash' ? 'bg-amber-600 text-white font-bold shadow-md shadow-amber-600/10' : 'bg-white border border-slate-200 dark:bg-dark-900 dark:border-slate-800 text-slate-500 font-medium'"
                                class="py-2 rounded-xl text-[10px] transition text-center flex items-center justify-center gap-1.5"><i class="fa-solid fa-money-bill-wave"></i> Cash</button>
                        <button @click="paymentMethod = 'Card'"
                                :class="paymentMethod === 'Card' ? 'bg-amber-600 text-white font-bold shadow-md shadow-amber-600/10' : 'bg-white border border-slate-200 dark:bg-dark-900 dark:border-slate-800 text-slate-500 font-medium'"
                                class="py-2 rounded-xl text-[10px] transition text-center flex items-center justify-center gap-1.5"><i class="fa-solid fa-credit-card"></i> Card</button>
                        <button @click="paymentMethod = 'Mobile'"
                                :class="paymentMethod === 'Mobile' ? 'bg-amber-600 text-white font-bold shadow-md shadow-amber-600/10' : 'bg-white border border-slate-200 dark:bg-dark-900 dark:border-slate-800 text-slate-500 font-medium'"
                                class="py-2 rounded-xl text-[10px] transition text-center flex items-center justify-center gap-1.5"><i class="fa-solid fa-mobile-screen-button"></i> Mobile</button>
                    </div>
                </div>

                <!-- Action Triggers -->
                <div class="grid grid-cols-4 gap-2">
                    <button @click="parkCart()"
                            class="col-span-1 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/30 text-indigo-700 dark:text-indigo-400 text-xs font-bold rounded-2xl py-3.5 transition flex items-center justify-center"
                            title="Park Order (Hold)">
                        <i class="fa-solid fa-circle-pause text-lg"></i>
                    </button>
                    <button @click="processPayment()"
                            :disabled="cart.length === 0"
                            class="col-span-3 bg-amber-600 hover:bg-amber-700 disabled:opacity-50 text-white font-bold rounded-2xl py-3.5 shadow-lg shadow-amber-600/20 active:scale-98 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-print"></i> Pay & Print Thermal
                    </button>
                </div>

            </div>
        </aside>
    </div>

    <!-- HIGH TECH THERMAL RECEIPT PRINTER MODAL -->
    <div x-cloak x-show="showReceipt" class="fixed inset-0 z-50 flex items-center justify-center" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div @click="showReceipt = false" x-show="showReceipt" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <!-- Receipt Box container with slide in animation -->
        <div x-show="showReceipt" x-transition.enter="transform transition ease-out duration-300" x-transition.enter-start="scale-90 opacity-0" x-transition.enter-end="scale-100 opacity-100"
             class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-2xl relative max-w-sm w-full z-10 border border-slate-100 dark:border-slate-800 text-slate-800 dark:text-slate-200">
            
            <button @click="showReceipt = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>

            <div class="text-center space-y-4">
                <i class="fa-solid fa-circle-check text-5xl text-emerald-500 animate-pulse"></i>
                <h3 class="font-serif text-lg font-bold">Transaction Confirmed</h3>
                <p class="text-xs text-slate-400">Order successfully completed and sent to kitchen!</p>
            </div>

            <!-- Simulated receipt ticket -->
            <div class="mt-6 p-4 bg-slate-50 dark:bg-slate-950 border border-dashed border-slate-350 dark:border-slate-800 font-mono text-[10px] space-y-4 rounded-xl leading-normal">
                <div class="text-center space-y-1">
                    <h4 class="font-bold text-sm tracking-widest uppercase">CaféFlow Roasters</h4>
                    <p>456 Gourmet Blvd, Artisanal CA</p>
                    <p>Tel: +1 234 567 890</p>
                </div>
                <div class="border-t border-dashed border-slate-300 dark:border-slate-800 pt-2 space-y-1">
                    <div class="flex justify-between"><span>DATE:</span><span x-text="receiptData.date"></span></div>
                    <div class="flex justify-between"><span>ORDER NO:</span><span x-text="'#' + receiptData.orderId"></span></div>
                    <div class="flex justify-between"><span>CASHIER:</span><span>{{ Auth::user()->name }}</span></div>
                    <div class="flex justify-between"><span>CUSTOMER:</span><span x-text="receiptData.customer"></span></div>
                </div>

                <!-- Receipt Items Loop -->
                <div class="border-t border-dashed border-slate-300 dark:border-slate-800 pt-2 space-y-1">
                    <div class="flex font-bold">
                        <span class="w-1/2">ITEM</span>
                        <span class="w-1/6 text-center">QTY</span>
                        <span class="w-1/3 text-right">TOTAL</span>
                    </div>
                    <template x-for="item in receiptData.items" :key="item.id">
                        <div class="flex">
                            <span class="w-1/2 truncate" x-text="item.name"></span>
                            <span class="w-1/6 text-center" x-text="item.qty"></span>
                            <span class="w-1/3 text-right" x-text="'$' + (item.price * item.qty).toFixed(2)"></span>
                        </div>
                    </template>
                </div>

                <!-- Calc summary -->
                <div class="border-t border-dashed border-slate-300 dark:border-slate-800 pt-2 space-y-1">
                    <div class="flex justify-between"><span>SUBTOTAL:</span><span x-text="'$' + receiptData.subtotal"></span></div>
                    <div class="flex justify-between"><span>TAX (5%):</span><span x-text="'$' + receiptData.tax"></span></div>
                    <div class="flex justify-between font-bold text-xs pt-1 border-t border-slate-200 dark:border-slate-800">
                        <span>TOTAL PAID:</span><span x-text="'$' + receiptData.total"></span>
                    </div>
                </div>

                <div class="border-t border-dashed border-slate-300 dark:border-slate-800 pt-3 text-center space-y-2">
                    <p class="uppercase text-[9px] tracking-widest font-bold">Thank you for your visit!</p>
                    <!-- Simulated thermal barcode -->
                    <div class="h-6 bg-slate-900 dark:bg-slate-300 w-44 mx-auto rounded flex items-center justify-center opacity-70">
                        <div class="h-full w-full flex justify-between px-1">
                            <template x-for="n in 25">
                                <div class="bg-white dark:bg-slate-950 h-full" :style="'width: ' + (Math.random() * 4 + 1) + 'px'"></div>
                            </template>
                        </div>
                    </div>
                    <p class="text-[8px] text-slate-400 leading-none">CaféFlow v2.0 POS Barcode</p>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button @click="showReceipt = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-bold transition">Close</button>
                <button @click="window.print()" class="flex-1 py-2.5 bg-amber-600 text-white rounded-xl text-xs font-bold transition shadow-md shadow-amber-600/20"><i class="fa-solid fa-print mr-1"></i> Print Ticket</button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function posTerminalHandler() {
        return {
            timeString: '',
            searchQuery: '',
            activeCat: 'all',
            cart: [],
            
            selectedCustomer: 'Walk-in',
            customerName: '',
            customerPhone: '',
            notes: '',
            paymentMethod: 'Cash',
            paymentStatus: 'Paid',
            
            parkedCarts: JSON.parse(localStorage.getItem('cafe_parked_carts') || '[]'),
            
            showReceipt: false,
            receiptData: {
                date: '',
                orderId: '',
                customer: '',
                items: [],
                subtotal: '0.00',
                tax: '0.00',
                total: '0.00'
            },

            init() {
                // Time Clock loop
                this.updateClock();
                setInterval(() => this.updateClock(), 1000);
            },

            updateClock() {
                const now = new Date();
                this.timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            },

            pickCustomer(value) {
                if (value === 'Walk-in') {
                    this.customerName = '';
                    this.customerPhone = '';
                } else {
                    const parts = value.split('|');
                    this.customerName = parts[0];
                    this.customerPhone = parts[1] ? 'Member ID: #' + parts[1] : '';
                }
            },

            addProduct(id, name, price, image) {
                const existIndex = this.cart.findIndex(i => i.id === id);
                if (existIndex > -1) {
                    this.cart[existIndex].qty += 1;
                } else {
                    this.cart.push({ id, name, price, image, qty: 1 });
                }
            },

            removeProduct(index) {
                this.cart.splice(index, 1);
            },

            updateQty(index, delta) {
                this.cart[index].qty += delta;
                if (this.cart[index].qty <= 0) {
                    this.removeProduct(index);
                }
            },

            getSubtotal() {
                return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            },

            getTax() {
                return this.getSubtotal() * 0.05;
            },

            getGrandTotal() {
                return this.getSubtotal() + this.getTax();
            },

            clearCart() {
                this.cart = [];
                this.customerName = '';
                this.customerPhone = '';
                this.notes = '';
                this.selectedCustomer = 'Walk-in';
            },

            parkCart() {
                if (this.cart.length === 0) {
                    alert('Cart is empty, cannot park.');
                    return;
                }
                const name = this.customerName || 'Parked Cart #' + (this.parkedCarts.length + 1);
                this.parkedCarts.push({
                    name,
                    cart: [...this.cart],
                    customerName: this.customerName,
                    customerPhone: this.customerPhone,
                    notes: this.notes,
                    itemsCount: this.cart.reduce((sum, item) => sum + item.qty, 0),
                    total: this.getGrandTotal()
                });
                localStorage.setItem('cafe_parked_carts', JSON.stringify(this.parkedCarts));
                this.clearCart();
            },

            recallCart(index) {
                const pCart = this.parkedCarts[index];
                this.cart = [...pCart.cart];
                this.customerName = pCart.customerName;
                this.customerPhone = pCart.customerPhone;
                this.notes = pCart.notes;
                
                // Remove from parked
                this.parkedCarts.splice(index, 1);
                localStorage.setItem('cafe_parked_carts', JSON.stringify(this.parkedCarts));
            },

            deleteParkedCart(index) {
                this.parkedCarts.splice(index, 1);
                localStorage.setItem('cafe_parked_carts', JSON.stringify(this.parkedCarts));
            },

            processPayment() {
                if (this.cart.length === 0) return;

                const payload = {
                    customer_name: this.customerName || 'Walk-in Customer',
                    customer_phone: this.customerPhone,
                    cart_items: JSON.stringify(this.cart),
                    payment_method: this.paymentMethod,
                    payment_status: 'Paid',
                    notes: this.notes,
                    _token: '{{ csrf_token() }}'
                };

                fetch("{{ route('pos.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Load thermal receipt data
                        this.receiptData = {
                            date: new Date().toLocaleString(),
                            orderId: data.order_id,
                            customer: payload.customer_name,
                            items: [...this.cart],
                            subtotal: this.getSubtotal().toFixed(2),
                            tax: this.getTax().toFixed(2),
                            total: this.getGrandTotal().toFixed(2)
                        };
                        
                        this.clearCart();
                        this.showReceipt = true;
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Server error connecting to checkout api.');
                });
            }
        }
    }
</script>
@endsection

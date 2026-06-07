@extends('layout')

@section('title', 'CaféFlow - Warm Artisanal Flavors')

@section('content')
<!-- Navigation -->
<nav class="sticky top-0 z-40 bg-white/80 dark:bg-dark-900/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 transition-colors" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-amber-600 to-amber-400 flex items-center justify-center shadow-lg shadow-amber-600/30">
                    <i class="fa-solid fa-mug-hot text-white text-xl"></i>
                </div>
                <span class="font-serif text-2xl font-bold tracking-tight bg-gradient-to-r from-amber-800 via-amber-600 to-amber-500 bg-clip-text text-transparent dark:from-amber-200 dark:via-amber-400 dark:to-amber-300">
                    CaféFlow
                </span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8 font-medium">
                <a href="#menu-section" class="hover:text-amber-600 transition">Our Menu</a>
                <a href="#about-section" class="hover:text-amber-600 transition">About Us</a>
                <a href="#reviews-section" class="hover:text-amber-600 transition">Testimonials</a>
                
                @auth
                    @if(Auth::user()->isStaff())
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white shadow-md shadow-amber-600/20 transition">
                            <i class="fa-solid fa-gauge"></i> Admin Dashboard
                        </a>
                    @else
                        <div class="flex items-center gap-4 border-l border-slate-200 dark:border-slate-700 pl-4">
                            <span class="text-sm font-semibold text-slate-500">Welcome, {{ Auth::user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-rose-500 hover:underline"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                            </form>
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-amber-500 dark:hover:border-amber-400 transition font-semibold text-sm">
                        <i class="fa-solid fa-user-lock mr-2"></i> Staff & Member Login
                    </a>
                @endauth

                <!-- Theme / Cart Icons -->
                <div class="flex items-center gap-4 pl-4 border-l border-slate-200 dark:border-slate-700">
                    <!-- Theme Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 transition">
                        <i x-cloak x-show="!darkMode" class="fa-solid fa-moon text-slate-600 text-lg"></i>
                        <i x-cloak x-show="darkMode" class="fa-solid fa-sun text-amber-400 text-lg"></i>
                    </button>
                    
                    <!-- Floating Cart Trigger -->
                    <button @click="$dispatch('open-cart')" class="relative p-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:hover:bg-amber-900/50 dark:text-amber-400 transition">
                        <i class="fa-solid fa-bag-shopping text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-amber-600 text-white rounded-full text-[10px] w-5 h-5 flex items-center justify-center font-bold" x-text="Object.values(cart).reduce((sum, item) => sum + item.qty, 0)">0</span>
                    </button>
                </div>
            </div>

            <!-- Mobile Hamburger -->
            <div class="flex md:hidden items-center gap-3">
                <!-- Theme Toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 transition">
                    <i x-cloak x-show="!darkMode" class="fa-solid fa-moon text-slate-600"></i>
                    <i x-cloak x-show="darkMode" class="fa-solid fa-sun text-amber-400"></i>
                </button>

                <!-- Mobile Cart -->
                <button @click="$dispatch('open-cart')" class="relative p-2 rounded-xl bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400 transition">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                    <span class="absolute -top-1 -right-1 bg-amber-600 text-white rounded-full text-[10px] w-4.5 h-4.5 flex items-center justify-center font-bold" x-text="Object.values(cart).reduce((sum, item) => sum + item.qty, 0)">0</span>
                </button>

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-slate-600 dark:text-slate-300">
                    <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div x-cloak x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-dark-900 px-4 py-4 space-y-3">
        <a href="#menu-section" @click="mobileMenuOpen = false" class="block py-2 text-lg font-medium">Our Menu</a>
        <a href="#about-section" @click="mobileMenuOpen = false" class="block py-2 text-lg font-medium">About Us</a>
        <a href="#reviews-section" @click="mobileMenuOpen = false" class="block py-2 text-lg font-medium">Testimonials</a>
        <hr class="border-slate-100 dark:border-slate-800">
        @auth
            <span class="block text-sm text-slate-500 py-1">Logged in as: <b>{{ Auth::user()->name }}</b></span>
            @if(Auth::user()->isStaff())
                <a href="{{ route('dashboard') }}" class="block text-center py-2.5 rounded-xl bg-amber-600 text-white font-semibold shadow-md"><i class="fa-solid fa-gauge mr-2"></i> Admin Dashboard</a>
            @endif
            <form action="{{ route('logout') }}" method="POST" class="block w-full">
                @csrf
                <button type="submit" class="w-full text-center py-2.5 rounded-xl border border-rose-200 dark:border-rose-800 text-rose-500 font-semibold"><i class="fa-solid fa-right-from-bracket mr-2"></i> Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="block text-center py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-sm">
                <i class="fa-solid fa-user-lock mr-2"></i> Staff & Member Login
            </a>
        @endauth
    </div>
</nav>

<!-- Home Application Container (Alpine Context) -->
<div x-data="homeCartHandler()" @open-cart.window="cartOpen = true" class="flex-1">
    
    <!-- Active Order Tracker Component -->
    @if($activeOrder)
    <div class="bg-amber-100/50 dark:bg-amber-950/20 border-b border-amber-200/50 dark:border-amber-800/30 py-6">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white dark:bg-dark-900 border border-amber-200 dark:border-amber-800/60 rounded-3xl p-6 shadow-xl relative overflow-hidden">
                <!-- Background ambient light glow -->
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl"></div>

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b border-slate-100 dark:border-slate-800 pb-4">
                    <div>
                        <span class="text-[10px] bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 px-3 py-1 rounded-full font-bold uppercase tracking-wider">Live Order Status</span>
                        <h3 class="text-xl font-bold mt-1 text-slate-900 dark:text-white">Order #{{ $activeOrder->id }} Tracking</h3>
                    </div>
                    <div class="text-right sm:text-left">
                        <p class="text-xs text-slate-400">Placed on: {{ $activeOrder->created_at->format('g:i A') }}</p>
                        <p class="text-sm font-bold text-amber-600 dark:text-amber-400">Total Paid/Due: ${{ number_format($activeOrder->total, 2) }}</p>
                    </div>
                </div>

                <!-- Process Steps Tracker -->
                <div class="grid grid-cols-4 gap-2 relative">
                    <!-- Progress Bar Behind -->
                    <div class="absolute top-5 left-[12.5%] right-[12.5%] h-1 bg-slate-100 dark:bg-slate-800 -z-0">
                        <div class="h-full bg-gradient-to-r from-amber-600 to-emerald-500 transition-all duration-700"
                             style="width: {{ 
                                $activeOrder->status === 'Pending' ? '0%' : (
                                $activeOrder->status === 'Preparing' ? '33.3%' : (
                                $activeOrder->status === 'Ready' ? '66.6%' : '100%'))
                             }}">
                        </div>
                    </div>

                    <!-- Step 1: Placed -->
                    <div class="flex flex-col items-center text-center z-10">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 transition-all duration-300 {{ 
                            in_array($activeOrder->status, ['Pending', 'Preparing', 'Ready', 'Completed']) 
                                ? 'bg-amber-600 border-amber-600 text-white shadow-md shadow-amber-600/30' 
                                : 'bg-slate-100 border-slate-200 text-slate-400 dark:bg-slate-800 dark:border-slate-700'
                        }}">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <span class="text-xs font-semibold mt-2 dark:text-slate-300">Placed</span>
                        <span class="text-[9px] text-slate-400">Waiting approval</span>
                    </div>

                    <!-- Step 2: Preparing -->
                    <div class="flex flex-col items-center text-center z-10">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 transition-all duration-300 {{ 
                            in_array($activeOrder->status, ['Preparing', 'Ready', 'Completed']) 
                                ? 'bg-amber-500 border-amber-500 text-white shadow-md shadow-amber-500/30' 
                                : 'bg-slate-100 border-slate-200 text-slate-400 dark:bg-slate-800 dark:border-slate-700'
                        }}">
                            <i class="fa-solid fa-fire-burner"></i>
                        </div>
                        <span class="text-xs font-semibold mt-2 dark:text-slate-300">Preparing</span>
                        <span class="text-[9px] text-slate-400">Kitchen cooking</span>
                    </div>

                    <!-- Step 3: Ready -->
                    <div class="flex flex-col items-center text-center z-10">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 transition-all duration-300 {{ 
                            in_array($activeOrder->status, ['Ready', 'Completed']) 
                                ? 'bg-indigo-600 border-indigo-600 text-white shadow-md shadow-indigo-600/30' 
                                : 'bg-slate-100 border-slate-200 text-slate-400 dark:bg-slate-800 dark:border-slate-700'
                        }}">
                            <i class="fa-solid fa-bell-concierge animate-bounce"></i>
                        </div>
                        <span class="text-xs font-semibold mt-2 dark:text-slate-300">Ready</span>
                        <span class="text-[9px] text-indigo-500 font-bold">Pick-up counter</span>
                    </div>

                    <!-- Step 4: Completed -->
                    <div class="flex flex-col items-center text-center z-10">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 transition-all duration-300 {{ 
                            $activeOrder->status === 'Completed' 
                                ? 'bg-emerald-600 border-emerald-600 text-white shadow-md shadow-emerald-600/30' 
                                : 'bg-slate-100 border-slate-200 text-slate-400 dark:bg-slate-800 dark:border-slate-700'
                        }}">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <span class="text-xs font-semibold mt-2 dark:text-slate-300">Done</span>
                        <span class="text-[9px] text-slate-400">Enjoy!</span>
                    </div>
                </div>

                @if($activeOrder->notes)
                <div class="mt-5 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-xs flex items-start gap-2 text-slate-500">
                    <i class="fa-solid fa-quote-left text-amber-500 mt-0.5"></i>
                    <span>Kitchen Note: "{{ $activeOrder->notes }}"</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Hero Section -->
    <header class="relative overflow-hidden pt-12 pb-24 md:pt-20 md:pb-36 bg-gradient-to-b from-amber-50/50 via-white to-transparent dark:from-dark-900 dark:to-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Left Hero info -->
                <div class="lg:col-span-7 space-y-6">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-100/70 border border-amber-200/50 text-amber-800 text-xs font-bold uppercase tracking-wider dark:bg-amber-950/50 dark:border-amber-900/50 dark:text-amber-400 animate-pulse">
                        <i class="fa-solid fa-mug-saucer"></i> Artisanal Coffee & Kitchen
                    </span>
                    <h1 class="text-5xl md:text-7xl font-serif font-black leading-tight text-slate-900 dark:text-white">
                        Brewing Joy,<br>One Cup at <span class="bg-gradient-to-r from-amber-600 via-amber-500 to-amber-400 bg-clip-text text-transparent italic font-serif">a Time</span>
                    </h1>
                    <p class="text-lg text-slate-500 dark:text-slate-400 leading-relaxed max-w-xl">
                        Welcome to CaféFlow, where rich, freshly roasted single-origin espresso meets hand-baked warm organic pastries. Skip the queue and order delicious brews directly to your seat or front door!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="#menu-section" class="px-8 py-4 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-bold shadow-lg shadow-amber-600/30 hover:shadow-xl transition text-center text-lg">
                            Explore Fresh Menu <i class="fa-solid fa-arrow-down mr-2"></i>
                        </a>
                        <a href="#about-section" class="px-8 py-4 rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-amber-500 hover:bg-amber-500/5 text-slate-700 dark:text-slate-300 font-bold transition text-center text-lg">
                            Our Heritage
                        </a>
                    </div>
                </div>

                <!-- Right Hero Image Stack -->
                <div class="lg:col-span-5 relative flex justify-center">
                    <div class="relative w-80 h-96 md:w-96 md:h-[450px]">
                        <!-- Background glow effect -->
                        <div class="absolute inset-0 bg-amber-500/10 dark:bg-amber-500/5 rounded-[40px] rotate-6 scale-105 filter blur-xl"></div>
                        
                        <!-- Premium image frame -->
                        <div class="absolute inset-0 rounded-[32px] overflow-hidden shadow-2xl rotate-3 border-4 border-white dark:border-slate-800 transition transform hover:rotate-0 hover:scale-102 duration-500">
                            <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80&w=800" alt="Specialty Latte Art" class="w-full h-full object-cover">
                        </div>

                        <!-- Mini Float badge 1 -->
                        <div class="absolute -bottom-6 -left-6 bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800 p-4 rounded-2xl shadow-xl flex items-center gap-3">
                            <div class="h-10 w-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-700 font-bold">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">4.9 Star Coffee</h4>
                                <p class="text-[10px] text-slate-400">12,000+ online reviews</p>
                            </div>
                        </div>

                        <!-- Mini Float badge 2 -->
                        <div class="absolute -top-6 -right-6 bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800 p-4 rounded-2xl shadow-xl flex items-center gap-3">
                            <div class="h-10 w-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-700 font-bold animate-bounce">
                                <i class="fa-solid fa-motorcycle"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Instant Delivery</h4>
                                <p class="text-[10px] text-slate-400">Under 15 minutes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Menu Section -->
    <section id="menu-section" class="py-20 bg-white dark:bg-dark-900 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-3 mb-12">
                <span class="text-amber-600 font-bold text-sm tracking-wider uppercase">House Craft Menu</span>
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 dark:text-white">Specially Prepared for You</h2>
                <div class="h-1 w-20 bg-amber-600 mx-auto rounded-full"></div>
            </div>

            <!-- Alpine Category Filters -->
            <div class="flex flex-wrap justify-center gap-3 mb-10">
                <button @click="activeCategory = 'all'" 
                        :class="activeCategory === 'all' ? 'bg-amber-600 text-white shadow-md shadow-amber-600/30' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700'"
                        class="px-6 py-2.5 rounded-full font-semibold transition text-sm flex items-center gap-2">
                    <i class="fa-solid fa-list-check"></i> All Items
                </button>
                @foreach($categories as $category)
                <button @click="activeCategory = 'cat-{{ $category->id }}'"
                        :class="activeCategory === 'cat-{{ $category->id }}' ? 'bg-amber-600 text-white shadow-md shadow-amber-600/30' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700'"
                        class="px-6 py-2.5 rounded-full font-semibold transition text-sm flex items-center gap-2">
                    <i class="fa-solid fa-{{ $category->icon ?: 'coffee' }}"></i> {{ $category->name }}
                </button>
                @endforeach
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($products as $product)
                <div x-show="activeCategory === 'all' || activeCategory === 'cat-{{ $product->category_id }}'"
                     x-transition.duration.400ms
                     class="group bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800/60 rounded-3xl p-4 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <!-- Product Image Frame -->
                        <div class="h-44 w-full rounded-2xl overflow-hidden relative mb-4">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            <!-- Category Float Badge -->
                            <span class="absolute top-3 left-3 bg-white/90 dark:bg-dark-900/90 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-slate-600 dark:text-amber-400">
                                {{ $product->category->name }}
                            </span>
                        </div>

                        <!-- Product details -->
                        <h3 class="font-bold text-lg text-slate-900 dark:text-white group-hover:text-amber-600 transition">{{ $product->name }}</h3>
                        <p class="text-xs text-slate-400 mt-1 line-clamp-2 h-8 leading-relaxed">{{ $product->description ?: 'Artisanal ingredients carefully prepared and brewed fresh.' }}</p>
                    </div>

                    <!-- Price & Action -->
                    <div class="flex justify-between items-center mt-6 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-2xl font-black text-amber-700 dark:text-amber-400 font-serif">${{ number_format($product->price, 2) }}</span>
                        
                        <button @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->image }}')"
                                class="h-10 w-10 bg-amber-600 group-hover:bg-amber-700 hover:scale-105 rounded-xl text-white flex items-center justify-center shadow-md shadow-amber-600/20 active:scale-95 transition">
                            <i class="fa-solid fa-plus text-sm"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about-section" class="py-20 bg-amber-50/20 dark:bg-dark-950 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                <!-- Left grid image frame -->
                <div class="lg:col-span-5 relative flex justify-center order-last lg:order-first">
                    <div class="relative w-80 h-96">
                        <div class="absolute inset-0 bg-amber-600/10 rounded-[32px] -rotate-6 scale-102 filter blur-md"></div>
                        <div class="absolute inset-0 rounded-[24px] overflow-hidden shadow-xl border-4 border-white dark:border-slate-800">
                            <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&q=80&w=600" alt="Cafe roasting process" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <!-- Right grid text info -->
                <div class="lg:col-span-7 space-y-6">
                    <span class="text-amber-600 font-bold text-sm tracking-wider uppercase">Our Craft & Legacy</span>
                    <h2 class="text-4xl font-serif font-bold text-slate-900 dark:text-white">Born out of Love for Coffee</h2>
                    <p class="text-slate-500 leading-relaxed dark:text-slate-400">
                        At CaféFlow, our beans are sourced directly from sustainable, organic micromill estates in Colombia, Ethiopia, and Sumatra. Hand-sorted and roasted weekly in-house, we unlock rich profiles ranging from fruity florals to sweet caramels and dark chocolates. 
                    </p>
                    <p class="text-slate-500 leading-relaxed dark:text-slate-400">
                        Pairing them with our premium service, modern POS interface, and swift online delivery ensures a seamless gourmet cafe dining experience right inside your cup.
                    </p>
                    <div class="grid grid-cols-2 gap-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <div>
                            <h4 class="font-serif text-3xl font-black text-amber-600">100%</h4>
                            <p class="text-xs text-slate-400 font-semibold uppercase mt-1">Single-Origin Arabica</p>
                        </div>
                        <div>
                            <h4 class="font-serif text-3xl font-black text-amber-600">Fresh</h4>
                            <p class="text-xs text-slate-400 font-semibold uppercase mt-1">Baked Goods Daily</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Review Section -->
    <section id="reviews-section" class="py-20 bg-white dark:bg-dark-900 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-3 mb-16">
                <span class="text-amber-600 font-bold text-sm tracking-wider uppercase">Loved by Locals</span>
                <h2 class="text-4xl font-serif font-bold text-slate-900 dark:text-white">Stories from Our Customers</h2>
                <div class="h-1 w-20 bg-amber-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-slate-50 dark:bg-slate-800/40 p-8 rounded-3xl border border-slate-100 dark:border-slate-800/60 shadow-sm space-y-4">
                    <div class="flex text-amber-500 gap-1 text-sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p class="text-slate-500 text-sm leading-relaxed italic">"CaféFlow has absolutely changed my morning routine. I place my latte order in the mobile cart while parking, and it's piping hot on the pick-up counter before I even push open the door!"</p>
                    <div class="flex items-center gap-3 pt-3">
                        <div class="h-10 w-10 rounded-full bg-amber-200 overflow-hidden"><img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=120" alt="Sarah J" class="w-full h-full object-cover"></div>
                        <div><h4 class="font-bold text-sm">Sarah Jenkins</h4><p class="text-[10px] text-slate-400 font-semibold">Regular Customer</p></div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-slate-50 dark:bg-slate-800/40 p-8 rounded-3xl border border-slate-100 dark:border-slate-800/60 shadow-sm space-y-4">
                    <div class="flex text-amber-500 gap-1 text-sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p class="text-slate-500 text-sm leading-relaxed italic">"The single-origin Ethiopian pour-over here is spectacular! It boasts incredible citrusy notes. Plus, the beautiful layout and lightning-fast checkout makes ordering incredibly enjoyable."</p>
                    <div class="flex items-center gap-3 pt-3">
                        <div class="h-10 w-10 rounded-full bg-amber-200 overflow-hidden"><img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=120" alt="David M" class="w-full h-full object-cover"></div>
                        <div><h4 class="font-bold text-sm">Marcus Vance</h4><p class="text-[10px] text-slate-400 font-semibold">Coffee Connoisseur</p></div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-slate-50 dark:bg-slate-800/40 p-8 rounded-3xl border border-slate-100 dark:border-slate-800/60 shadow-sm space-y-4">
                    <div class="flex text-amber-500 gap-1 text-sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p class="text-slate-500 text-sm leading-relaxed italic">"Hands down, the best chocolate almond croissant in the city! Flaky, buttered, and loaded. Ordering online is seamless, and their live tracker keeps me perfectly informed of my order state."</p>
                    <div class="flex items-center gap-3 pt-3">
                        <div class="h-10 w-10 rounded-full bg-amber-200 overflow-hidden"><img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&q=80&w=120" alt="Elena R" class="w-full h-full object-cover"></div>
                        <div><h4 class="font-bold text-sm">Elena Rostova</h4><p class="text-[10px] text-slate-400 font-semibold">Local Foodie</p></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-4">
                <a href="#" class="flex items-center gap-2">
                    <div class="h-9 w-9 rounded-xl bg-amber-600 flex items-center justify-center text-white"><i class="fa-solid fa-mug-hot"></i></div>
                    <span class="font-serif text-xl font-bold tracking-tight text-white">CaféFlow</span>
                </a>
                <p class="text-xs">Premium coffee & artisanal food POS platform for high-performance ordering.</p>
            </div>
            <div>
                <h4 class="text-white font-bold text-sm mb-4">Hours of Operation</h4>
                <ul class="text-xs space-y-2">
                    <li>Monday - Friday: 7:00 AM - 8:00 PM</li>
                    <li>Saturday - Sunday: 8:00 AM - 10:00 PM</li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold text-sm mb-4">Our Location</h4>
                <p class="text-xs leading-relaxed">456 Gourmet Boulevard, Suite 101<br>Artisanal District, CA 90210</p>
            </div>
            <div>
                <h4 class="text-white font-bold text-sm mb-4">Follow Us</h4>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-instagram text-lg"></i></a>
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-facebook text-lg"></i></a>
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-twitter text-lg"></i></a>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 text-center mt-12 pt-8 border-t border-slate-800 text-[10px]">
            &copy; 2026 CaféFlow POS & Ordering. Built using lightweight ("Laravel Lite") design.
        </div>
    </footer>

    <!-- Alpine Shopping Cart Drawer Overlay -->
    <div x-cloak x-show="cartOpen" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div @click="cartOpen = false" x-show="cartOpen" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div class="absolute inset-y-0 right-0 max-w-full flex pl-10">
            <div x-show="cartOpen" x-transition.enter="transform transition ease-in-out duration-400" x-transition.enter-start="translate-x-full" x-transition.enter-end="translate-x-0" x-transition.leave="transform transition ease-in-out duration-400" x-transition.leave-start="translate-x-0" x-transition.leave-end="translate-x-full" 
                 class="w-screen max-w-md bg-white dark:bg-dark-900 border-l border-slate-100 dark:border-slate-800/80 shadow-2xl flex flex-col justify-between h-full">
                
                <!-- Drawer Header -->
                <div class="p-6 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                    <h2 class="text-lg font-bold flex items-center gap-2 text-slate-950 dark:text-white">
                        <i class="fa-solid fa-cart-shopping text-amber-600"></i> Your Ordering Cart
                    </h2>
                    <button @click="cartOpen = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>

                <!-- Drawer Cart Items list -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    <!-- If Cart Empty -->
                    <template x-if="Object.keys(cart).length === 0">
                        <div class="text-center py-16 space-y-4">
                            <i class="fa-solid fa-mug-hot text-5xl text-amber-200 dark:text-slate-700 animate-bounce"></i>
                            <p class="text-sm font-semibold text-slate-400">Your cart is feeling incredibly empty. Add some fresh coffee!</p>
                            <button @click="cartOpen = false" class="px-6 py-2.5 bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 rounded-full font-bold text-xs">Browse Menu</button>
                        </div>
                    </template>

                    <!-- Cart Loop -->
                    <template x-for="(item, id) in cart" :key="id">
                        <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800/50 p-3 rounded-2xl">
                            <div class="h-16 w-16 rounded-xl overflow-hidden shrink-0">
                                <img :src="item.image" :alt="item.name" class="h-full w-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-sm text-slate-900 dark:text-white truncate" x-text="item.name"></h4>
                                <p class="text-xs text-amber-700 dark:text-amber-400 font-bold" x-text="'$' + (item.price * item.qty).toFixed(2)"></p>
                                
                                <!-- Adjust Qty -->
                                <div class="flex items-center gap-2 mt-2">
                                    <button @click="updateQty(id, -1)" class="w-6 h-6 border border-slate-200 dark:border-slate-700 text-slate-500 rounded-md flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition"><i class="fa-solid fa-minus text-[9px]"></i></button>
                                    <span class="text-xs font-bold w-4 text-center" x-text="item.qty"></span>
                                    <button @click="updateQty(id, 1)" class="w-6 h-6 border border-slate-200 dark:border-slate-700 text-slate-500 rounded-md flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition"><i class="fa-solid fa-plus text-[9px]"></i></button>
                                </div>
                            </div>
                            <button @click="removeFromCart(id)" class="text-rose-500 hover:text-rose-700 p-2"><i class="fa-solid fa-trash-can text-sm"></i></button>
                        </div>
                    </template>
                </div>

                <!-- Drawer Checkout Form -->
                <div class="p-6 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50" x-show="Object.keys(cart).length > 0">
                    <form action="{{ route('online-order.store') }}" method="POST" @submit="submitCart($event)">
                        @csrf
                        <input type="hidden" name="cart_items" :value="JSON.stringify(cart)">
                        
                        <!-- Totals -->
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-sm font-semibold text-slate-500">Subtotal</span>
                            <span class="text-2xl font-serif font-black text-amber-800 dark:text-amber-400" x-text="'$' + getCartTotal().toFixed(2)"></span>
                        </div>

                        <!-- Fields -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Your Name</label>
                                <input type="text" name="customer_name" required value="{{ Auth::check() ? Auth::user()->name : '' }}"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-900 focus:border-amber-500 focus:ring-0 text-sm transition outline-none" placeholder="e.g. John Doe">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Phone Number</label>
                                <input type="text" name="customer_phone" required
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-900 focus:border-amber-500 focus:ring-0 text-sm transition outline-none" placeholder="e.g. +1 234 567 890">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Cooking / Delivery Notes</label>
                                <textarea name="notes" rows="2"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-900 focus:border-amber-500 focus:ring-0 text-sm transition outline-none resize-none" placeholder="e.g. Extra hot coffee, no sugar please..."></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Payment Method</label>
                                <select name="payment_method" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-900 focus:border-amber-500 focus:ring-0 text-sm transition outline-none">
                                    <option value="Cash">Cash on Pick-up</option>
                                    <option value="Card">Credit / Debit Card</option>
                                    <option value="Mobile">Mobile MobilePay / ApplePay</option>
                                </select>
                            </div>

                            @guest
                            <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-xl text-[10px] text-amber-800 dark:text-amber-400 border border-amber-200/50">
                                <i class="fa-solid fa-circle-info mr-1"></i> Tip: <b><a href="{{ route('login') }}" class="underline">Login / Register</a></b> to save order history & track live status!
                            </div>
                            @endguest

                            <button type="submit" class="w-full py-4 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-2xl shadow-lg shadow-amber-600/20 active:scale-98 transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-truck-fast"></i> Place Online Order Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function homeCartHandler() {
        return {
            activeCategory: 'all',
            cart: JSON.parse(localStorage.getItem('cafe_cart') || '{}'),
            cartOpen: false,

            addToCart(id, name, price, image) {
                if (this.cart[id]) {
                    this.cart[id].qty += 1;
                } else {
                    this.cart[id] = { id, name, price, image, qty: 1 };
                }
                this.saveCart();
                
                // Alert visual effect
                this.cartOpen = true;
            },

            removeFromCart(id) {
                delete this.cart[id];
                this.saveCart();
            },

            updateQty(id, delta) {
                if (!this.cart[id]) return;
                this.cart[id].qty += delta;
                if (this.cart[id].qty <= 0) {
                    this.removeFromCart(id);
                } else {
                    this.saveCart();
                }
            },

            getCartTotal() {
                return Object.values(this.cart).reduce((sum, item) => sum + (item.price * item.qty), 0);
            },

            saveCart() {
                localStorage.setItem('cafe_cart', JSON.stringify(this.cart));
            },

            submitCart(event) {
                if (Object.keys(this.cart).length === 0) {
                    event.preventDefault();
                    alert('Cart is empty.');
                    return;
                }
                
                // Clear cart locally upon placement
                setTimeout(() => {
                    localStorage.removeItem('cafe_cart');
                    this.cart = {};
                }, 100);
            }
        }
    }
</script>
@endsection

@extends('layout')

@section('title', 'CaféFlow - Menu Catalog CRUD')

@section('content')
<div class="flex h-screen bg-slate-50 dark:bg-dark-950 overflow-hidden text-slate-800 dark:text-slate-200 transition-colors duration-300" 
     x-data="{ sidebarOpen: true, tab: 'products' }">
    
    <!-- SIDEBAR PANEL -->
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" 
           class="h-full bg-white dark:bg-dark-900 border-r border-slate-100 dark:border-slate-850 shrink-0 flex flex-col justify-between p-4 transition-all duration-300 z-30">
        
        <div class="space-y-6">
            <!-- Sidebar Header / Logo -->
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="h-10 w-10 bg-amber-600 rounded-xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-amber-600/30">
                    <i class="fa-solid fa-mug-hot text-lg"></i>
                </div>
                <div class="transition-opacity duration-300 min-w-0" x-show="sidebarOpen">
                    <h2 class="font-serif text-lg font-bold truncate leading-tight">CaféFlow</h2>
                    <span class="text-[9px] font-bold text-amber-600 uppercase tracking-widest">Back-Office</span>
                </div>
            </div>

            <!-- Sidebar Navigation Links -->
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 text-sm transition">
                    <i class="fa-solid fa-chart-line text-lg shrink-0 text-amber-500"></i>
                    <span class="truncate" x-show="sidebarOpen">Dashboard Stats</span>
                </a>

                <a href="{{ route('pos') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 text-sm transition">
                    <i class="fa-solid fa-cash-register text-lg shrink-0 text-amber-500"></i>
                    <span class="truncate" x-show="sidebarOpen">POS Terminal</span>
                </a>

                <a href="{{ route('orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 text-sm transition">
                    <i class="fa-solid fa-fire-burner text-lg shrink-0 text-emerald-500"></i>
                    <span class="truncate" x-show="sidebarOpen">Kitchen Orders</span>
                </a>

                <a href="{{ route('menu') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-amber-50 text-amber-800 dark:bg-amber-950/40 dark:text-amber-300 font-semibold text-sm">
                    <i class="fa-solid fa-mug-saucer text-lg shrink-0"></i>
                    <span class="truncate" x-show="sidebarOpen">Menu Items CRUD</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer Action (Logout & Collapse) -->
        <div class="space-y-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 text-sm transition">
                <i class="fa-solid fa-globe text-lg shrink-0 text-teal-500"></i>
                <span class="truncate" x-show="sidebarOpen">View Public Site</span>
            </a>

            <form action="{{ route('logout') }}" method="POST" class="inline w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/20 font-medium text-slate-500 text-sm transition">
                    <i class="fa-solid fa-right-from-bracket text-lg shrink-0 text-rose-500"></i>
                    <span class="truncate" x-show="sidebarOpen">Sign Out</span>
                </button>
            </form>

            <button @click="sidebarOpen = !sidebarOpen" class="w-full hidden md:flex items-center justify-center p-2 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-400 transition">
                <i class="fa-solid" :class="sidebarOpen ? 'fa-angle-left' : 'fa-angle-right'"></i>
            </button>
        </div>
    </aside>

    <!-- MAIN PRODUCT & CATEGORIES CONTENT WRAPPER -->
    <main class="flex-1 flex flex-col overflow-y-auto">
        <!-- Top header -->
        <header class="bg-white dark:bg-dark-900 border-b border-slate-100 dark:border-slate-850 px-8 py-4 flex items-center justify-between sticky top-0 z-20 transition-colors">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 transition">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Menu Catalog Desk</h1>
            </div>

            <!-- Profile Info and Toggler -->
            <div class="flex items-center gap-6">
                <!-- Theme Toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 transition">
                    <i x-cloak x-show="!darkMode" class="fa-solid fa-moon text-slate-600"></i>
                    <i x-cloak x-show="darkMode" class="fa-solid fa-sun text-amber-400"></i>
                </button>

                <div class="flex items-center gap-3 pl-6 border-l border-slate-100 dark:border-slate-800">
                    <div class="h-10 w-10 bg-amber-600 rounded-xl flex items-center justify-center font-bold text-white shadow-md text-sm uppercase">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-sm leading-tight">{{ Auth::user()->name }}</h4>
                        <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                            {{ Auth::user()->role }}
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <!-- DASHBOARD CONTAINER GRID -->
        <div class="p-8 space-y-6">
            <!-- Tabs selection -->
            <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6 mb-6">
                <button @click="tab = 'products'" 
                        :class="tab === 'products' ? 'border-amber-600 text-amber-600 font-bold dark:text-amber-400' : 'border-transparent text-slate-400 font-semibold'"
                        class="pb-3 border-b-2 text-sm transition">
                    Menu Products (Items)
                </button>
                <button @click="tab = 'categories'" 
                        :class="tab === 'categories' ? 'border-amber-600 text-amber-600 font-bold dark:text-amber-400' : 'border-transparent text-slate-400 font-semibold'"
                        class="pb-3 border-b-2 text-sm transition">
                    Menu Categories
                </button>
            </div>

            <!-- TAB 1: PRODUCTS / ITEMS MANAGEMENT -->
            <div x-cloak x-show="tab === 'products'" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Products list Table -->
                <div class="lg:col-span-8 bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800/80 p-6 rounded-[32px] shadow-sm">
                    <h3 class="font-serif text-lg font-bold text-slate-900 dark:text-white mb-6">Menu Catalog</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-400 uppercase">
                                    <th class="pb-3">Thumbnail</th>
                                    <th class="pb-3">Product Name</th>
                                    <th class="pb-3">Category</th>
                                    <th class="pb-3 text-right">Price</th>
                                    <th class="pb-3 text-center">Status</th>
                                    <th class="pb-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-sm">
                                @forelse($categories->flatMap->products as $product)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition duration-150" x-data="{ editing: false }">
                                    <!-- Image Thumbnail -->
                                    <td class="py-4">
                                        <div class="h-10 w-10 rounded-lg overflow-hidden shrink-0 bg-slate-100 border dark:border-slate-800">
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                        </div>
                                    </td>
                                    
                                    <!-- Product Info / Inline Edit -->
                                    <td class="py-4 font-semibold text-slate-900 dark:text-white">
                                        <template x-if="!editing">
                                            <div>
                                                <span>{{ $product->name }}</span>
                                                <p class="text-[10px] text-slate-400 max-w-xs font-normal truncate mt-0.5">{{ $product->description ?: 'No description provided.' }}</p>
                                            </div>
                                        </template>

                                        <!-- Simple inline edit fallback modal -->
                                        <template x-if="editing">
                                            <form action="{{ route('menu.product.update', $product) }}" method="POST" class="space-y-2 p-3 bg-slate-50 dark:bg-slate-800 rounded-xl">
                                                @csrf
                                                <div class="space-y-1.5">
                                                    <input type="text" name="name" required value="{{ $product->name }}" class="w-full px-2 py-1 border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-900 rounded text-xs">
                                                    <input type="number" step="0.01" name="price" required value="{{ $product->price }}" class="w-full px-2 py-1 border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-900 rounded text-xs">
                                                    <input type="text" name="image" value="{{ $product->image }}" class="w-full px-2 py-1 border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-900 rounded text-[10px]">
                                                    <textarea name="description" class="w-full px-2 py-1 border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-900 rounded text-[10px] resize-none" rows="2">{{ $product->description }}</textarea>
                                                    <select name="category_id" class="w-full px-2 py-1 border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-900 rounded text-xs">
                                                        @foreach($categories as $c)
                                                        <option value="{{ $c->id }}" {{ $product->category_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                    <!-- Available -->
                                                    <div class="flex items-center gap-1.5 py-1">
                                                        <input type="checkbox" name="available" value="1" id="avail-{{ $product->id }}" {{ $product->available ? 'checked' : '' }} class="rounded border-slate-200 text-amber-600 h-3.5 w-3.5">
                                                        <label for="avail-{{ $product->id }}" class="text-[10px] text-slate-400 font-bold">Available in menu</label>
                                                    </div>
                                                </div>
                                                <div class="flex gap-2">
                                                    <button type="submit" class="px-2 py-1 bg-emerald-600 text-white rounded text-[10px] font-bold">Save</button>
                                                    <button type="button" @click="editing = false" class="px-2 py-1 border border-slate-350 text-slate-500 rounded text-[10px]">Cancel</button>
                                                </div>
                                            </form>
                                        </template>
                                    </td>
                                    
                                    <!-- Category name -->
                                    <td class="py-4 text-xs font-semibold text-slate-500">{{ $product->category->name }}</td>
                                    
                                    <!-- Price -->
                                    <td class="py-4 text-right font-black font-serif text-amber-700 dark:text-amber-400">${{ number_format($product->price, 2) }}</td>
                                    
                                    <!-- Available tag status -->
                                    <td class="py-4 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $product->available ? 'bg-emerald-500/10 text-emerald-600' : 'bg-rose-500/10 text-rose-600' }}">
                                            {{ $product->available ? 'Available' : 'Sold Out' }}
                                        </span>
                                    </td>

                                    <!-- Delete and Edit triggers -->
                                    <td class="py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button @click="editing = !editing" class="p-1.5 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg text-slate-500 text-xs transition"><i class="fa-solid fa-pen-to-square"></i></button>
                                            
                                            <form action="{{ route('menu.product.delete', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')">
                                                @csrf
                                                <button type="submit" class="p-1.5 border border-slate-200 dark:border-slate-800 hover:bg-rose-50 dark:hover:bg-rose-950/20 text-rose-500 rounded-lg text-xs transition"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 font-semibold italic"><i class="fa-solid fa-inbox text-2xl mb-2 block"></i> No products found in menu. Add one on the right side panel!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Products Creator form -->
                <div class="lg:col-span-4 bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800/80 p-6 rounded-[32px] shadow-sm space-y-4">
                    <h3 class="font-serif text-lg font-bold text-slate-900 dark:text-white mb-2">Create New Product</h3>
                    <form action="{{ route('menu.product.store') }}" method="POST" class="space-y-4 text-xs font-semibold">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Product Title</label>
                            <input type="text" name="name" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 rounded-xl text-xs outline-none focus:border-amber-500 transition" placeholder="e.g. Double Espresso">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Pricing ($)</label>
                            <input type="number" step="0.01" name="price" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 rounded-xl text-xs outline-none focus:border-amber-500 transition" placeholder="e.g. 4.95">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Menu Category Selector</label>
                            <select name="category_id" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 rounded-xl text-xs outline-none focus:border-amber-500 transition">
                                <option value="" disabled selected>Pick category...</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Product Description</label>
                            <textarea name="description" rows="3" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 rounded-xl text-xs outline-none focus:border-amber-500 transition resize-none" placeholder="e.g. Fresh organic dark-roasted double shot..."></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Product Photo URL (Online)</label>
                            <input type="url" name="image" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 rounded-xl text-xs outline-none focus:border-amber-500 transition" placeholder="e.g. https://images.unsplash.com/photo-...">
                        </div>

                        <button type="submit" class="w-full py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-2xl shadow-lg shadow-amber-600/20 active:scale-98 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-plus"></i> Save Product Item
                        </button>
                    </form>
                </div>
            </div>

            <!-- TAB 2: CATEGORIES MANAGEMENT -->
            <div x-cloak x-show="tab === 'categories'" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Categories list Table -->
                <div class="lg:col-span-8 bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800/80 p-6 rounded-[32px] shadow-sm">
                    <h3 class="font-serif text-lg font-bold text-slate-900 dark:text-white mb-6">Menu Categories</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-400 uppercase">
                                    <th class="pb-3">Icon Glyph</th>
                                    <th class="pb-3">Category Name</th>
                                    <th class="pb-3">Friendly Slug</th>
                                    <th class="pb-3 text-center">Connected Products</th>
                                    <th class="pb-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-sm">
                                @forelse($categories as $category)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition duration-150" x-data="{ editing: false }">
                                    <!-- Glyph Icon -->
                                    <td class="py-4">
                                        <div class="h-9 w-9 rounded-xl bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-400 flex items-center justify-center text-sm">
                                            <i class="fa-solid fa-{{ $category->icon ?: 'coffee' }}"></i>
                                        </div>
                                    </td>
                                    
                                    <!-- Category Name / Edit inline -->
                                    <td class="py-4 font-bold text-slate-900 dark:text-white">
                                        <template x-if="!editing">
                                            <span>{{ $category->name }}</span>
                                        </template>

                                        <template x-if="editing">
                                            <form action="{{ route('menu.category.update', $category) }}" method="POST" class="space-y-2 p-2.5 bg-slate-50 dark:bg-slate-800 rounded-xl">
                                                @csrf
                                                <input type="text" name="name" required value="{{ $category->name }}" class="w-full px-2 py-1 border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-900 rounded text-xs">
                                                <input type="text" name="icon" value="{{ $category->icon }}" class="w-full px-2 py-1 border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-900 rounded text-xs" placeholder="e.g. coffee, cake">
                                                <div class="flex gap-2">
                                                    <button type="submit" class="px-2 py-1 bg-emerald-600 text-white rounded text-[10px] font-bold">Save</button>
                                                    <button type="button" @click="editing = false" class="px-2 py-1 border border-slate-350 text-slate-500 rounded text-[10px]">Cancel</button>
                                                </div>
                                            </form>
                                        </template>
                                    </td>
                                    
                                    <!-- Friendly Slug -->
                                    <td class="py-4 text-xs font-semibold text-slate-400 font-mono">{{ $category->slug }}</td>
                                    
                                    <!-- Connected products count -->
                                    <td class="py-4 text-center font-bold text-amber-600">{{ $category->products->count() }} Items</td>

                                    <!-- Delete and Edit triggers -->
                                    <td class="py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button @click="editing = !editing" class="p-1.5 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg text-slate-500 text-xs transition"><i class="fa-solid fa-pen-to-square"></i></button>
                                            
                                            <form action="{{ route('menu.category.delete', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete category {{ addslashes($category->name) }} and all associated products?')">
                                                @csrf
                                                <button type="submit" class="p-1.5 border border-slate-200 dark:border-slate-800 hover:bg-rose-50 dark:hover:bg-rose-950/20 text-rose-500 rounded-lg text-xs transition"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 font-semibold italic"><i class="fa-solid fa-inbox text-2xl mb-2 block"></i> No categories found. Create one on the right hand panel!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Category Creator Form -->
                <div class="lg:col-span-4 bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800/80 p-6 rounded-[32px] shadow-sm space-y-4">
                    <h3 class="font-serif text-lg font-bold text-slate-900 dark:text-white mb-2">Create Category</h3>
                    <form action="{{ route('menu.category.store') }}" method="POST" class="space-y-4 text-xs font-semibold">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Category Title</label>
                            <input type="text" name="name" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 rounded-xl text-xs outline-none focus:border-amber-500 transition" placeholder="e.g. Cold Brews">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">FontAwesome Icon Glyph (No prefix)</label>
                            <input type="text" name="icon" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 rounded-xl text-xs outline-none focus:border-amber-500 transition" placeholder="e.g. coffee, bread-slice, cake-candles">
                            <span class="block text-[8px] text-slate-400 mt-1 font-normal leading-normal">Use any FontAwesome suffix string: e.g. `mug-hot`, `bread-slice`, `cake-candles`, `glass-water`, `ice-cream`.</span>
                        </div>

                        <button type="submit" class="w-full py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-2xl shadow-lg shadow-amber-600/20 active:scale-98 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-plus"></i> Save Category Info
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>

</div>
@endsection

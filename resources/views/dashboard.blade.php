@extends('layout')

@section('title', 'CaféFlow - Administrative Dashboard')

@section('head')
<!-- Chart.js CDN for modern metrics plotting -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="flex h-screen bg-slate-50 dark:bg-dark-950 overflow-hidden text-slate-800 dark:text-slate-200 transition-colors duration-300" 
     x-data="{ sidebarOpen: true }">
    
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
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-amber-50 text-amber-800 dark:bg-amber-950/40 dark:text-amber-300 font-semibold text-sm">
                    <i class="fa-solid fa-chart-line text-lg shrink-0"></i>
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

                <a href="{{ route('menu') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 text-sm transition">
                    <i class="fa-solid fa-mug-saucer text-lg shrink-0 text-indigo-500"></i>
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

    <!-- MAIN DASHBOARD CONTENT AREA -->
    <main class="flex-1 flex flex-col overflow-y-auto">
        <!-- Dashboard Top Header Bar -->
        <header class="bg-white dark:bg-dark-900 border-b border-slate-100 dark:border-slate-850 px-8 py-4 flex items-center justify-between sticky top-0 z-20 transition-colors">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 transition">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Admin Dashboard</h1>
            </div>

            <!-- Profile Info and Toggler -->
            <div class="flex items-center gap-6">
                <!-- Theme Toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 transition">
                    <i x-cloak x-show="!darkMode" class="fa-solid fa-moon text-slate-600"></i>
                    <i x-cloak x-show="darkMode" class="fa-solid fa-sun text-amber-400"></i>
                </button>

                <!-- Profile user info -->
                <div class="flex items-center gap-3 border-l border-slate-100 dark:border-slate-800 pl-6">
                    <div class="h-10 w-10 bg-amber-600 rounded-xl flex items-center justify-center font-bold text-white shadow-md text-sm uppercase">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-sm leading-tight">{{ Auth::user()->name }}</h4>
                        <!-- Role tag label badge -->
                        <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                            {{ Auth::user()->role }}
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <!-- DASHBOARD CONTAINER GRID -->
        <div class="p-8 space-y-8">
            
            <!-- CORE METRIC STAT CARDS -->
            <section class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Stat 1: Revenue -->
                <div class="bg-gradient-to-tr from-amber-600 to-amber-500 p-6 rounded-3xl text-white shadow-xl shadow-amber-600/10 flex justify-between items-center relative overflow-hidden">
                    <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                    <div class="space-y-1 relative z-10">
                        <span class="text-xs font-semibold uppercase tracking-wider text-amber-100">Today's Sales</span>
                        <h2 class="text-3xl font-black font-serif">${{ number_format($todaySales, 2) }}</h2>
                        <p class="text-[10px] text-amber-100/70"><i class="fa-solid fa-chart-line"></i> Total collected cash/card</p>
                    </div>
                    <div class="h-12 w-12 bg-white/20 rounded-2xl flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-sack-dollar"></i></div>
                </div>

                <!-- Stat 2: Today Orders -->
                <div class="bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800/80 p-6 rounded-3xl shadow-sm flex justify-between items-center">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Today's Orders</span>
                        <h2 class="text-3xl font-black text-slate-900 dark:text-white">{{ $todayOrders }}</h2>
                        <p class="text-[10px] text-emerald-500 font-semibold"><i class="fa-solid fa-plus"></i> Newly recorded logs</p>
                    </div>
                    <div class="h-12 w-12 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-xl text-slate-500 shrink-0"><i class="fa-solid fa-receipt"></i></div>
                </div>

                <!-- Stat 3: Pending -->
                <div class="bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800/80 p-6 rounded-3xl shadow-sm flex justify-between items-center">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending Orders</span>
                        <h2 class="text-3xl font-black text-amber-600 dark:text-amber-500 flex items-center gap-2">
                            {{ $pendingOrders }}
                            @if($pendingOrders > 0)
                            <span class="w-3.5 h-3.5 bg-amber-500 rounded-full animate-ping inline-block"></span>
                            @endif
                        </h2>
                        <p class="text-[10px] text-amber-500 font-semibold">Waiting kitchen preparation</p>
                    </div>
                    <div class="h-12 w-12 bg-amber-50 dark:bg-amber-950/40 rounded-2xl flex items-center justify-center text-xl text-amber-600 shrink-0"><i class="fa-solid fa-clock"></i></div>
                </div>

                <!-- Stat 4: Completed -->
                <div class="bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800/80 p-6 rounded-3xl shadow-sm flex justify-between items-center">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Completed Orders</span>
                        <h2 class="text-3xl font-black text-emerald-600 dark:text-emerald-500">{{ $completedOrders }}</h2>
                        <p class="text-[10px] text-slate-400">Total delivered items</p>
                    </div>
                    <div class="h-12 w-12 bg-emerald-50 dark:bg-emerald-950/40 rounded-2xl flex items-center justify-center text-xl text-emerald-600 shrink-0"><i class="fa-solid fa-circle-check"></i></div>
                </div>
            </section>

            <!-- WEEKLY ANALYTICS CHART & OVERVIEW -->
            <section class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Weekly sales dynamic plot (Chart.js) -->
                <div class="lg:col-span-8 bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800/80 p-6 rounded-[32px] shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="font-serif text-lg font-bold text-slate-900 dark:text-white">Weekly Performance</h3>
                            <p class="text-xs text-slate-400">Aggregated revenue over past 7 days</p>
                        </div>
                        <span class="text-[10px] bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 px-3 py-1 rounded-full font-bold uppercase">7 Days Track</span>
                    </div>
                    
                    <div class="relative h-72">
                        <canvas id="weeklySalesChart"></canvas>
                    </div>
                </div>

                <!-- Cafe summary details side card -->
                <div class="lg:col-span-4 bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800/80 p-6 rounded-[32px] shadow-sm flex flex-col justify-between">
                    <div>
                        <h3 class="font-serif text-lg font-bold text-slate-900 dark:text-white mb-4">Stock Registry</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-400 rounded-xl flex items-center justify-center text-sm"><i class="fa-solid fa-mug-hot"></i></div>
                                    <span class="text-sm font-semibold">Active Categories</span>
                                </div>
                                <span class="font-black text-sm text-amber-700" x-text="'{{ $categoriesCount }}' + ' Categories'"></span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-400 rounded-xl flex items-center justify-center text-sm"><i class="fa-solid fa-mug-saucer"></i></div>
                                    <span class="text-sm font-semibold">Active Menu Products</span>
                                </div>
                                <span class="font-black text-sm text-indigo-700" x-text="'{{ $productsCount }}' + ' Items'"></span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800 mt-6 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Quick Access Hub</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('pos') }}" class="px-4 py-3 rounded-2xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold text-center transition flex items-center justify-center gap-2"><i class="fa-solid fa-cash-register"></i> POS Screen</a>
                            <a href="{{ route('orders') }}" class="px-4 py-3 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold text-center transition flex items-center justify-center gap-2"><i class="fa-solid fa-fire-burner"></i> Kitchen Grid</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- RECENT ORDERS LIST REGISTRY -->
            <section class="bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800/80 p-6 rounded-[32px] shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="font-serif text-lg font-bold text-slate-900 dark:text-white">Recent Transactions</h3>
                        <p class="text-xs text-slate-400">Latest online & counter orders logged in CaféFlow</p>
                    </div>
                    <a href="{{ route('orders') }}" class="text-xs text-amber-600 dark:text-amber-400 font-bold hover:underline">View All Orders <i class="fa-solid fa-chevron-right text-[10px]"></i></a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-400 uppercase">
                                <th class="pb-3">Order ID</th>
                                <th class="pb-3">Customer</th>
                                <th class="pb-3">Origin</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-right">Revenue</th>
                                <th class="pb-3 text-center">Payment</th>
                                <th class="pb-3 text-center">Fast Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-sm">
                            @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition duration-150">
                                <td class="py-4 font-bold">#{{ $order->id }}</td>
                                <td class="py-4">
                                    <div class="font-bold text-slate-900 dark:text-slate-100">{{ $order->customer_name ?: 'Walk-in Customer' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $order->customer_phone ?: 'Counter purchase' }}</div>
                                </td>
                                <td class="py-4 font-semibold text-xs text-slate-500">
                                    <span class="px-2 py-0.5 rounded-full {{ $order->type === 'POS' ? 'bg-blue-50 text-blue-800 dark:bg-blue-950/40 dark:text-blue-300' : 'bg-violet-50 text-violet-800 dark:bg-violet-950/40 dark:text-violet-300' }}">
                                        {{ $order->type }}
                                    </span>
                                </td>
                                <td class="py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold {{ 
                                        $order->status === 'Pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-400' : (
                                        $order->status === 'Preparing' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-400' : (
                                        $order->status === 'Ready' ? 'bg-cyan-100 text-cyan-800 dark:bg-cyan-950 dark:text-cyan-400' : (
                                        $order->status === 'Completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-400' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-400')))
                                    }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-4 text-right font-black font-serif text-amber-800 dark:text-amber-400">${{ number_format($order->total, 2) }}</td>
                                <td class="py-4 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $order->payment_status === 'Paid' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-rose-500/10 text-rose-600' }}">
                                        {{ $order->payment_status }}
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <!-- Dynamic instant status toggle to preparing or ready -->
                                    @if($order->status === 'Pending')
                                    <form action="{{ route('orders.status', $order) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Preparing">
                                        <button type="submit" class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold transition shadow-sm shadow-amber-500/10">Start Cooking</button>
                                    </form>
                                    @elseif($order->status === 'Preparing')
                                    <form action="{{ route('orders.status', $order) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Ready">
                                        <button type="submit" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition shadow-sm shadow-indigo-600/10">Mark Ready</button>
                                    </form>
                                    @elseif($order->status === 'Ready')
                                    <form action="{{ route('orders.status', $order) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Completed">
                                        <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-sm shadow-emerald-600/10">Complete</button>
                                    </form>
                                    @else
                                    <span class="text-xs text-slate-400 font-semibold italic"><i class="fa-solid fa-circle-check text-emerald-500"></i> Finalized</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 font-semibold italic"><i class="fa-solid fa-inbox text-2xl mb-2 block"></i> No orders recorded today.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>

</div>
@endsection

@section('scripts')
<script>
    // Config Chart.js curve gradient week chart
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('weeklySalesChart').getContext('2d');
        
        // Gradient Fill
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(217, 119, 6, 0.4)');
        gradient.addColorStop(1, 'rgba(217, 119, 6, 0.0)');

        const weeklySalesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($daysLabel) !!},
                datasets: [{
                    label: 'Sales Revenue ($)',
                    data: {!! json_encode($salesData) !!},
                    borderColor: '#d97706',
                    borderWidth: 3,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.4,
                    pointBackgroundColor: '#d97706',
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#d97706',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                family: 'Outfit'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                family: 'Outfit'
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

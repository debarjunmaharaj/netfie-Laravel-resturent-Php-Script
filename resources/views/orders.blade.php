@extends('layout')

@section('title', 'CaféFlow - Kitchen Orders Tracker')

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
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 text-sm transition">
                    <i class="fa-solid fa-chart-line text-lg shrink-0 text-amber-500"></i>
                    <span class="truncate" x-show="sidebarOpen">Dashboard Stats</span>
                </a>

                <a href="{{ route('pos') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 text-sm transition">
                    <i class="fa-solid fa-cash-register text-lg shrink-0 text-amber-500"></i>
                    <span class="truncate" x-show="sidebarOpen">POS Terminal</span>
                </a>

                <a href="{{ route('orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-amber-50 text-amber-800 dark:bg-amber-950/40 dark:text-amber-300 font-semibold text-sm">
                    <i class="fa-solid fa-fire-burner text-lg shrink-0"></i>
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

    <!-- MAIN KITCHEN ORDERS WRAPPER -->
    <main class="flex-1 flex flex-col overflow-y-auto">
        <!-- Top header -->
        <header class="bg-white dark:bg-dark-900 border-b border-slate-100 dark:border-slate-850 px-8 py-4 flex items-center justify-between sticky top-0 z-20 transition-colors">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 transition">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Kitchen Order Desk</h1>
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

        <!-- KITCHEN TICKETS CONTAINER -->
        <div class="p-8 space-y-6">
            
            <!-- Filter Tabs Nav -->
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-4">
                <div class="flex flex-wrap gap-2">
                    @foreach(['All', 'Pending', 'Preparing', 'Ready', 'Completed', 'Cancelled'] as $status)
                    <a href="{{ route('orders', ['status' => $status]) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ 
                           (request('status', 'All') === $status) 
                               ? 'bg-amber-600 text-white shadow-md shadow-amber-600/20' 
                               : 'bg-white hover:bg-slate-50 text-slate-500 border border-slate-100 dark:bg-dark-900 dark:border-slate-800 dark:text-slate-400' 
                       }}">
                        {{ $status }}
                    </a>
                    @endforeach
                </div>
                
                <span class="text-xs text-slate-400 font-bold"><i class="fa-solid fa-rotate text-amber-500 mr-1 animate-spin"></i> Live Auto-Sync Active</span>
            </div>

            <!-- Tickets Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($orders as $order)
                <!-- Individual Ticket Card -->
                <div class="bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-850 rounded-[24px] shadow-sm p-5 flex flex-col justify-between space-y-4 relative overflow-hidden transition"
                     x-data="{ 
                         itemsChecked: {}, 
                         orderTime: new Date('{{ $order->created_at->toIso8601String() }}'),
                         ageMinutes: 0,
                         updateAge() {
                             this.ageMinutes = Math.floor((new Date() - this.orderTime) / 60000);
                         }
                     }"
                     x-init="updateAge(); setInterval(() => updateAge(), 30000)">
                    
                    <!-- Glow background indicator for statuses -->
                    <div class="absolute top-0 inset-x-0 h-1.5 {{ 
                        $order->status === 'Pending' ? 'bg-amber-500' : (
                        $order->status === 'Preparing' ? 'bg-indigo-500' : (
                        $order->status === 'Ready' ? 'bg-cyan-500' : (
                        $order->status === 'Completed' ? 'bg-emerald-500' : 'bg-rose-500')))
                    }}"></div>

                    <!-- Ticket Header -->
                    <div class="flex justify-between items-start pt-1">
                        <div>
                            <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500">
                                {{ $order->type }} Order
                            </span>
                            <h3 class="font-serif text-lg font-bold text-slate-900 dark:text-white mt-1">#{{ $order->id }} - <span class="font-sans text-sm">{{ $order->customer_name ?: 'Walk-in Customer' }}</span></h3>
                        </div>

                        <!-- Elapsed Age Warning -->
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                <i class="fa-regular fa-clock text-amber-500"></i> <span x-text="ageMinutes + 'm ago'">0m ago</span>
                            </span>
                            
                            <!-- Late Order Badge (Flashing orange/red if preparing/pending > 15m) -->
                            <template x-if="ageMinutes >= 15 && ['Pending', 'Preparing'].includes('{{ $order->status }}')">
                                <span class="inline-block text-[8px] font-black uppercase text-white bg-rose-600 px-2 py-0.5 rounded-full mt-1 animate-pulse"><i class="fa-solid fa-triangle-exclamation"></i> Late ticket</span>
                            </template>
                        </div>
                    </div>

                    <!-- Items Checklist Area -->
                    <div class="border-t border-slate-100 dark:border-slate-800 pt-3 space-y-2 flex-1">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Recipe Checklist</span>
                        <div class="space-y-2">
                            @foreach($order->items as $item)
                            <div class="flex items-center gap-3 select-none" 
                                 x-data="{ checked: false }">
                                <input type="checkbox" x-model="checked" id="chk-{{ $item->id }}"
                                       class="rounded border-slate-350 dark:border-slate-700 text-emerald-600 focus:ring-0 h-4.5 w-4.5 cursor-pointer">
                                <label for="chk-{{ $item->id }}" 
                                       :class="checked ? 'line-through text-slate-400 italic' : 'font-semibold text-slate-700 dark:text-slate-300'"
                                       class="flex-1 text-xs cursor-pointer">
                                    <span class="text-amber-600 dark:text-amber-500 font-bold" x-text="'{{ $item->quantity }}x'"></span> 
                                    {{ $item->product->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Notes Panel -->
                    @if($order->notes)
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/20 border border-amber-200/50 dark:border-amber-900/30 rounded-xl text-xs text-amber-800 dark:text-amber-400">
                        <i class="fa-solid fa-circle-info mr-1 text-amber-500"></i> Notes: <b>"{{ $order->notes }}"</b>
                    </div>
                    @endif

                    <!-- Pricing & Status indicators -->
                    <div class="flex justify-between items-center text-xs pt-3 border-t border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-400">Revenue Flow</span>
                            <span class="text-sm font-black font-serif text-amber-800 dark:text-amber-400">${{ number_format($order->total, 2) }}</span>
                            <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded {{ $order->payment_status === 'Paid' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-rose-500/10 text-rose-600' }} ml-1">
                                {{ $order->payment_status }}
                            </span>
                        </div>

                        <!-- Live Action workflow dropdown -->
                        <div class="flex gap-2">
                            <!-- Pending Status Action -->
                            @if($order->status === 'Pending')
                            <form action="{{ route('orders.status', $order) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="Preparing">
                                <button type="submit" class="px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition shadow-md shadow-amber-500/10 flex items-center gap-1"><i class="fa-solid fa-fire-burner"></i> Start Cook</button>
                            </form>

                            <!-- Preparing Status Action -->
                            @elseif($order->status === 'Preparing')
                            <form action="{{ route('orders.status', $order) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="Ready">
                                <button type="submit" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-600/10 flex items-center gap-1"><i class="fa-solid fa-bell-concierge"></i> Finish</button>
                            </form>

                            <!-- Ready Status Action -->
                            @elseif($order->status === 'Ready')
                            <form action="{{ route('orders.status', $order) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="Completed">
                                <button type="submit" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-emerald-600/10 flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Handover</button>
                            </form>
                            
                            @else
                            <span class="text-xs text-slate-400 font-semibold italic flex items-center gap-1"><i class="fa-solid fa-circle-check text-emerald-500"></i> Done</span>
                            @endif

                            <!-- Cancel Button -->
                            @if(!in_array($order->status, ['Completed', 'Cancelled']))
                            <form action="{{ route('orders.status', $order) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="Cancelled">
                                <button type="submit" class="p-2 border border-slate-200 dark:border-slate-800 hover:bg-rose-50 dark:hover:bg-rose-950/20 text-rose-500 rounded-xl transition" title="Cancel Ticket"><i class="fa-solid fa-trash-can text-sm"></i></button>
                            </form>
                            @endif
                        </div>
                    </div>

                </div>
                @empty
                <div class="col-span-full bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-850 p-12 text-center text-slate-400 rounded-[32px] italic">
                    <i class="fa-solid fa-circle-check text-4xl text-emerald-500 animate-pulse mb-3 block"></i> No orders found in this status. Everything is clean and prepared!
                </div>
                @endforelse
            </div>

            <!-- Paginate links -->
            <div class="pt-4">
                {{ $orders->appends(request()->query())->links() }}
            </div>

        </div>
    </main>

</div>
@endsection

@extends('layout')

@section('title', 'CaféFlow - Secure Authentication')

@section('content')
<div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-amber-50/50 via-white to-amber-100/10 dark:from-dark-950 dark:via-dark-900 dark:to-dark-950 transition-colors">
    <!-- Back to Website Floating Button -->
    <a href="{{ route('home') }}" class="fixed top-5 left-5 px-4 py-2 bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800 text-xs font-bold rounded-xl shadow-md hover:text-amber-600 transition flex items-center gap-2">
        <i class="fa-solid fa-arrow-left-long"></i> Back to Homepage
    </a>

    <!-- Auth Card Panel -->
    <div class="max-w-4xl w-full bg-white dark:bg-dark-900 border border-slate-100 dark:border-slate-800/80 rounded-[32px] shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12 min-h-[500px]" 
         x-data="{ isLogin: true }">
        
        <!-- Left Side Branding Pane (Amber Glow) -->
        <div class="md:col-span-5 bg-gradient-to-br from-amber-900 via-amber-800 to-amber-750 p-10 text-white flex flex-col justify-between relative overflow-hidden">
            <!-- Background Vector Glows -->
            <div class="absolute -top-10 -left-10 w-44 h-44 bg-amber-500/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-10 -right-10 w-44 h-44 bg-amber-400/10 rounded-full blur-2xl"></div>

            <div class="space-y-2 relative z-10">
                <div class="h-10 w-10 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-md">
                    <i class="fa-solid fa-mug-hot text-amber-300 text-xl"></i>
                </div>
                <h2 class="font-serif text-2xl font-bold tracking-tight">CaféFlow</h2>
                <p class="text-xs text-amber-200">POS & Online Ordering</p>
            </div>

            <div class="space-y-4 relative z-10">
                <i class="fa-solid fa-quote-left text-3xl text-amber-400"></i>
                <p class="text-sm text-amber-100 leading-relaxed font-light">"A cup of premium single-origin coffee is not just a drink; it's a sensory gateway into artisan passion, community warmth, and focused flow."</p>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-amber-300">CaféFlow Roasters</h4>
                    <p class="text-[10px] text-amber-200/60 font-semibold">Artisanal Brew Masters</p>
                </div>
            </div>

            <div class="text-[10px] text-amber-300/50 relative z-10">
                &copy; 2026 CaféFlow. All rights secured.
            </div>
        </div>

        <!-- Right Side Form Fields Pane -->
        <div class="md:col-span-7 p-10 flex flex-col justify-center">
            
            <!-- Auth Error Alerts -->
            @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-2xl text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <div class="flex items-center gap-2"><i class="fa-solid fa-circle-exclamation text-rose-500"></i> {{ $error }}</div>
                @endforeach
            </div>
            @endif

            <!-- Tabs Switcher -->
            <div class="flex border-b border-slate-100 dark:border-slate-800 mb-8">
                <button @click="isLogin = true" 
                        :class="isLogin ? 'border-amber-600 text-amber-600 font-bold dark:text-amber-400' : 'border-transparent text-slate-400 font-semibold'"
                        class="flex-1 text-center pb-3 border-b-2 text-sm transition">
                    Sign In (Staff & Guest)
                </button>
                <button @click="isLogin = false" 
                        :class="!isLogin ? 'border-amber-600 text-amber-600 font-bold dark:text-amber-400' : 'border-transparent text-slate-400 font-semibold'"
                        class="flex-1 text-center pb-3 border-b-2 text-sm transition">
                    Sign Up (Customer Member)
                </button>
            </div>

            <!-- LOGIN FORM -->
            <div x-cloak x-show="isLogin" x-transition.opacity.duration.300ms class="space-y-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Welcome Back!</h3>
                    <p class="text-xs text-slate-400 mt-1">Please enter your credentials to access your portal.</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Email Address</label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                   class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 focus:border-amber-500 focus:ring-0 text-sm outline-none transition" placeholder="e.g. admin@cafeflow.com">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400">Password</label>
                            <a href="#" class="text-[10px] text-amber-600 dark:text-amber-400 hover:underline">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="password" name="password" required
                                   class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 focus:border-amber-500 focus:ring-0 text-sm outline-none transition" placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Remember me checkbox -->
                    <div class="flex items-center gap-2 py-2">
                        <input type="checkbox" name="remember" id="remember" class="rounded border-slate-200 text-amber-600 focus:ring-0 h-4 w-4">
                        <label for="remember" class="text-xs text-slate-400 font-semibold cursor-pointer">Remember my device</label>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-2xl shadow-lg shadow-amber-600/20 active:scale-98 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-check"></i> Sign In to Portal
                    </button>
                </form>
            </div>

            <!-- REGISTER FORM -->
            <div x-cloak x-show="!isLogin" x-transition.opacity.duration.300ms class="space-y-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Create Member Account</h3>
                    <p class="text-xs text-slate-400 mt-1">Get access to custom history, faster checkouts, and tracking.</p>
                </div>

                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Your Full Name</label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                   class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 focus:border-amber-500 focus:ring-0 text-sm outline-none transition" placeholder="e.g. Alice Smith">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Email Address</label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                   class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 focus:border-amber-500 focus:ring-0 text-sm outline-none transition" placeholder="e.g. alice@gmail.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Password</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="password" name="password" required
                                   class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 focus:border-amber-500 focus:ring-0 text-sm outline-none transition" placeholder="Min. 6 characters">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Confirm Password</label>
                        <div class="relative">
                            <i class="fa-solid fa-shield-halved absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="password" name="password_confirmation" required
                                   class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-dark-900 focus:border-amber-500 focus:ring-0 text-sm outline-none transition" placeholder="Confirm your password">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-2xl shadow-lg shadow-amber-600/20 active:scale-98 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-user-plus"></i> Join as Member Customer
                    </button>
                </form>
            </div>

            <!-- Demo logins help hint block -->
            <div class="mt-8 p-3 bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-2xl text-[10px] text-slate-400 leading-normal">
                <i class="fa-solid fa-lightbulb text-amber-500 mr-1"></i> <b>Demo Logins (Auto-seeded):</b><br>
                • <b>Admin:</b> `admin@cafeflow.com` / `password`<br>
                • <b>Cashier:</b> `cashier@cafeflow.com` / `password`<br>
                • <b>Kitchen:</b> `kitchen@cafeflow.com` / `password`
            </div>

        </div>

    </div>
</div>
@endsection

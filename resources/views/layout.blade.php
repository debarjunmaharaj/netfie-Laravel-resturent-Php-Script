<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CaféFlow POS & Online Ordering - Modern, lightweight Cafe POS & Online Ordering platform.">
    <title>@yield('title', 'CaféFlow - Premium POS & Ordering')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- FontAwesome v6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        amber: {
                            50: '#fefaf6',
                            100: '#fdf5eb',
                            500: '#d97706',
                            600: '#b45309',
                            700: '#92400e',
                            800: '#78350f',
                            900: '#451a03',
                        },
                        dark: {
                            50: '#f8fafc',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Micro-animations & Scrollbar Styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        /* Smooth Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        .dark ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Custom Glowing Card Glassmorphism */
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass-panel {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
    @yield('head')
</head>
<body class="h-full font-sans antialiased text-slate-800 dark:text-slate-200 bg-amber-50/50 dark:bg-dark-950 transition-colors duration-300">

    <!-- Flash Messages -->
    <div class="fixed top-5 right-5 z-50 flex flex-col gap-3 max-w-md" x-data="{ 
        showSuccess: {{ session('success') ? 'true' : 'false' }},
        showError: {{ session('error') ? 'true' : 'false' }},
        successMsg: '{{ session('success') }}',
        errorMsg: '{{ session('error') }}'
    }">
        <!-- Success Alert -->
        <template x-if="showSuccess">
            <div x-transition class="flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-950/80 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-xl shadow-xl glass-panel">
                <i class="fa-solid fa-circle-check text-xl text-emerald-500"></i>
                <div class="flex-1 text-sm font-semibold" x-text="successMsg"></div>
                <button @click="showSuccess = false" class="hover:opacity-70"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>
        </template>

        <!-- Error Alert -->
        <template x-if="showError">
            <div x-transition class="flex items-center gap-3 p-4 bg-rose-50 dark:bg-rose-950/80 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-xl shadow-xl glass-panel">
                <i class="fa-solid fa-triangle-exclamation text-xl text-rose-500"></i>
                <div class="flex-1 text-sm font-semibold" x-text="errorMsg"></div>
                <button @click="showError = false" class="hover:opacity-70"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>
        </template>
    </div>

    <!-- Main Container -->
    <div class="min-h-screen flex flex-col">
        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>

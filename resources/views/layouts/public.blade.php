<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="
    $watch('darkMode', val => {
        localStorage.setItem('darkMode', val);
        document.documentElement.classList.toggle('dark', val);
    });
    // Initialize dark mode from localStorage or system preference
    darkMode = localStorage.getItem('darkMode') === 'true' || 
              (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Apply') - {{ config('app.name', 'Certificate Management') }}</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon-white.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('images/favicon-white.svg') }}" type="image/svg+xml">
    
    <style>
        /* Logo Styling */
        .logo-container {
            width: 200px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-svg {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: all 0.3s ease;
            /* Light mode - normal logo */
            filter: none;
        }
        
        /* Dark mode - make logo white */
        .dark .logo-svg {
            filter: brightness(0) invert(1);
        }
    </style>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen transition-colors duration-300">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex-shrink-0">
                        <a href="{{ url('/') }}" class="flex items-center">
                            <div class="logo-container">
                                <img 
                                    src="{{ asset('images/logo_light.svg') }}" 
                                    alt="{{ config('app.name') }}" 
                                    class="logo-svg"
                                />
                            </div>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button 
                            @click="darkMode = !darkMode" 
                            class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
                            :aria-label="darkMode ? 'Switch to light mode' : 'Switch to dark mode'"
                        >
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border-t border-gray-200/50 dark:border-gray-700/50 mt-auto">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400 mt-2 sm:mt-0">
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Terms of Service</a>
                        <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Support</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Toast Notifications -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="flex-1">{{ session('success') }}</span>
                <button @click="show = false" class="ml-2 text-green-200 hover:text-white">×</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span class="flex-1">{{ session('error') }}</span>
                <button @click="show = false" class="ml-2 text-red-200 hover:text-white">×</button>
            </div>
        </div>
    @endif
</body>
</html>

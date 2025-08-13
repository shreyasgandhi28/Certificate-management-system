<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: window.innerWidth >= 1024 }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val)); $watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Certificate Management') }}</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon-white.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('images/favicon-white.svg') }}" type="image/svg+xml">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Enhanced Dark Mode + Fixed Click Handling -->
    <style>
        html.dark {
            color-scheme: dark !important;
        }
        
        /* Sidebar structure with proper toggle */
        .sidebar-container {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            color: #111827;
            transform: translateX(0);
            transition: transform 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }
        
        .sidebar-container.hidden {
            transform: translateX(-100%);
        }
        
        /* Main content that adapts to sidebar state */
        .main-content {
            margin-left: 240px;
            min-height: 100vh;
            transition: all 0.3s ease;
            background-color: #f9fafb;
            color: #111827;
            position: relative;
            z-index: 1;
            width: calc(100% - 240px);
            flex: 1;
            max-width: 100%;
        }
        
        .main-content.expanded {
            margin-left: 0;
            width: 100%;
        }
        
        /* FORCE dark mode colors */
        .dark .sidebar-container {
            background-color: #1f2937 !important;
            color: #f9fafb !important;
        }
        
        .dark .main-content {
            background-color: #111827 !important;
            color: #f9fafb !important;
        }
        
        /* Modern Top Bar Design */
        .topbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            color: #ffffff;
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 30;
            height: 4.5rem;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
        }
        
        .topbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            pointer-events: none;
        }
        
        .dark .topbar {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
            box-shadow: 0 4px 20px rgba(30, 41, 59, 0.3) !important;
            color: #f9fafb !important;
        }
        
        .dark .topbar::before {
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
        }
        
        /* Modern Sidebar Header Design */
        .sidebar-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            height: 4.5rem;
        }
        
        .sidebar-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            pointer-events: none;
        }
        
        .dark .sidebar-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2) !important;
        }
        
        .dark .sidebar-header::before {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }
        
        /* Text contrast classes */
        .text-primary {
            color: #111827 !important;
        }
        
        .dark .text-primary {
            color: #f9fafb !important;
        }
        
        .text-secondary {
            color: #6b7280 !important;
        }
        
        .dark .text-secondary {
            color: #d1d5db !important;
        }
        
        .nav-link {
            color: #374151 !important;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        
        .dark .nav-link {
            color: #e5e7eb !important;
        }
        
        .dark .nav-link:hover {
            background-color: #374151 !important;
            color: #f9fafb !important;
        }
        
        /* Card text */
        .card-text-primary {
            color: #111827 !important;
        }
        
        .dark .card-text-primary {
            color: #f9fafb !important;
        }
        
        .card-text-secondary {
            color: #6b7280 !important;
        }
        
        .dark .card-text-secondary {
            color: #d1d5db !important;
        }
        
        /* Table text */
        .table-text {
            color: #111827 !important;
        }
        
        .dark .table-text {
            color: #f9fafb !important;
        }
        
        .table-text-muted {
            color: #6b7280 !important;
        }
        
        .dark .table-text-muted {
            color: #9ca3af !important;
        }
        
        /* Borders */
        .border-custom {
            border-color: #e5e7eb !important;
        }
        
        .dark .border-custom {
            border-color: #374151 !important;
        }
        
        /* Mobile backdrop - only visible when explicitly shown */
        .mobile-backdrop {
            display: none;
        }
        
        @media (max-width: 1024px) {
            .sidebar-container {
                transform: translateX(-100%);
            }
            
            .sidebar-container.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
            
            /* Only show backdrop on mobile when sidebar is open */
            .mobile-backdrop.show {
                display: block !important;
            }
        }
        
        /* User initials */
        .user-initials {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
        }
        
        /* Modern Company Logo Styling */
        .sidebar-logo-container {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar-logo-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%);
            pointer-events: none;
        }
        
        .sidebar-logo-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }
        
        .sidebar-logo-svg {
            width: 48px;
            height: 48px;
            filter: brightness(0) invert(1);
            transition: all 0.3s ease;
            object-fit: contain;
            position: relative;
            z-index: 1;
        }
        
        /* Light mode - normal logo */
        .sidebar-logo-svg {
            filter: brightness(0) invert(1);
        }
        
        /* Dark mode - make logo white/visible */
        .dark .sidebar-logo-svg {
            filter: brightness(0) invert(1);
        }
        
        /* Enhanced Button Styles for Better Visibility */
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
            color: #ffffff !important;
            border: 1px solid #2563eb !important;
            box-shadow: 0 1px 3px 0 rgba(37, 99, 235, 0.1), 0 1px 2px 0 rgba(37, 99, 235, 0.06) !important;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
            border-color: #1d4ed8 !important;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.1), 0 2px 4px -1px rgba(37, 99, 235, 0.06) !important;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669) !important;
            color: #ffffff !important;
            border: 1px solid #059669 !important;
            box-shadow: 0 1px 3px 0 rgba(16, 185, 129, 0.1), 0 1px 2px 0 rgba(16, 185, 129, 0.06) !important;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #059669, #047857) !important;
            border-color: #047857 !important;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1), 0 2px 4px -1px rgba(16, 185, 129, 0.06) !important;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            color: #ffffff !important;
            border: 1px solid #dc2626 !important;
            box-shadow: 0 1px 3px 0 rgba(239, 68, 68, 0.1), 0 1px 2px 0 rgba(239, 68, 68, 0.06) !important;
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
            border-color: #b91c1c !important;
            box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.1), 0 2px 4px -1px rgba(239, 68, 68, 0.06) !important;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706) !important;
            color: #ffffff !important;
            border: 1px solid #d97706 !important;
            box-shadow: 0 1px 3px 0 rgba(245, 158, 11, 0.1), 0 1px 2px 0 rgba(245, 158, 11, 0.06) !important;
        }
        
        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706, #b45309) !important;
            border-color: #b45309 !important;
            box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.1), 0 2px 4px -1px rgba(245, 158, 11, 0.06) !important;
        }
        
        .btn-purple {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed) !important;
            color: #ffffff !important;
            border: 1px solid #7c3aed !important;
            box-shadow: 0 1px 3px 0 rgba(139, 92, 246, 0.1), 0 1px 2px 0 rgba(139, 92, 246, 0.06) !important;
        }
        
        .btn-purple:hover {
            background: linear-gradient(135deg, #7c3aed, #6d28d9) !important;
            border-color: #6d28d9 !important;
            box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.1), 0 2px 4px -1px rgba(139, 92, 246, 0.06) !important;
        }
        
        /* Dark mode button adjustments */
        .dark .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
            border-color: #1d4ed8 !important;
        }
        
        .dark .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af) !important;
            border-color: #1e40af !important;
        }
        
        .dark .btn-success {
            background: linear-gradient(135deg, #10b981, #047857) !important;
            border-color: #047857 !important;
        }
        
        .dark .btn-success:hover {
            background: linear-gradient(135deg, #047857, #065f46) !important;
            border-color: #065f46 !important;
        }
        
        .dark .btn-danger {
            background: linear-gradient(135deg, #ef4444, #b91c1c) !important;
            border-color: #b91c1c !important;
        }
        
        .dark .btn-danger:hover {
            background: linear-gradient(135deg, #b91c1c, #991b1b) !important;
            border-color: #991b1b !important;
        }
        
        /* Chart legend colors */
        .legend-dot-pending {
            background-color: #fbbf24 !important;
            width: 12px !important;
            height: 12px !important;
            border-radius: 50% !important;
            margin-right: 12px !important;
            flex-shrink: 0 !important;
        }
        
        .legend-dot-verified {
            background-color: #10b981 !important;
            width: 12px !important;
            height: 12px !important;
            border-radius: 50% !important;
            margin-right: 12px !important;
            flex-shrink: 0 !important;
        }
        
        .legend-dot-rejected {
            background-color: #ef4444 !important;
            width: 12px !important;
            height: 12px !important;
            border-radius: 50% !important;
            margin-right: 12px !important;
            flex-shrink: 0 !important;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased">
    <!-- FIXED Mobile backdrop - Only closes sidebar when clicked directly -->
    <div 
        x-show="sidebarOpen && window.innerWidth < 1024" 
        x-transition.opacity 
        @click.self="sidebarOpen = false" 
        class="mobile-backdrop fixed inset-0 z-40 bg-black bg-opacity-50"
        :class="sidebarOpen && window.innerWidth < 1024 ? 'show' : ''"
    ></div>

    <!-- WORKING Sidebar with Fixed Click Handling -->
    <div 
        x-show="sidebarOpen || window.innerWidth >= 1024" 
        @click.away="if (window.innerWidth < 1024) sidebarOpen = false"
        class="sidebar-container bg-white dark:bg-gray-800" 
        :class="{ 'hidden': !sidebarOpen }"
    >
        <!-- Sidebar Header -->
        <div class="sidebar-header py-4">
            <div class="flex items-center justify-start pl-6">
                <!-- Favicon Logo - Shows original in light mode, white in dark mode -->
                <div class="flex-shrink-0 mr-3">
                    <!-- Original favicon for light mode -->
                    <img src="{{ asset('images/favicon.svg') }}" alt="Logo" class="h-10 w-10 dark:hidden">
                    <!-- White favicon for dark mode -->
                    <img src="{{ asset('images/favicon-white.svg') }}" alt="Logo" class="h-10 w-10 hidden dark:block">
                </div>
                <h1 class="text-base font-semibold tracking-tight text-gray-900 dark:text-white font-sans">CERTIFICATE MANAGER</h1>
            </div>
        </div>

        <!-- User Info -->
        <div class="px-6 py-4 border-b border-custom flex-shrink-0">
            <div class="flex items-center">
                <div class="w-10 h-10 user-initials rounded-full flex items-center justify-center">
                    <span class="text-sm font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-primary truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-secondary truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto">
            <nav class="mt-6 px-3 pb-4">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'nav-link hover:bg-gray-100 dark:hover:bg-gray-700' }} transition-colors">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Applications -->
                    <a href="{{ route('admin.applicants.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.applicants.*') ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'nav-link hover:bg-gray-100 dark:hover:bg-gray-700' }} transition-colors">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Applications
                    </a>

                    <!-- Documents -->
                    <a href="{{ route('admin.uploads.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.uploads.*') ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'nav-link hover:bg-gray-100 dark:hover:bg-gray-700' }} transition-colors">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Documents
                    </a>

                    <!-- Certificates -->
                    <a href="{{ route('admin.certificates.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.certificates.*') ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'nav-link hover:bg-gray-100 dark:hover:bg-gray-700' }} transition-colors">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Certificates
                    </a>

                    <!-- Users -->
                    <a href="{{ route('admin.users.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'nav-link hover:bg-gray-100 dark:hover:bg-gray-700' }} transition-colors">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m3 5.197V9a3 3 0 00-6 0v2.25"></path>
                        </svg>
                        Users
                    </a>
                </div>
            </nav>
        </div>

        <!-- Fixed Logout -->
        <div class="p-4 border-t border-custom flex-shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="group flex items-center w-full px-3 py-2 text-sm font-medium nav-link rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content with Fixed Click Handling -->
    <div :class="sidebarOpen ? 'main-content' : 'main-content expanded'">
        <!-- Top Header with WORKING Hamburger -->
        <div class="topbar">
            <div class="w-full">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- WORKING Hamburger Menu -->
                        <button 
                            @click.stop="sidebarOpen = !sidebarOpen" 
                            class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white transition-colors duration-200"
                            aria-label="Toggle sidebar"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="relative z-10">
                            <h1 class="text-2xl font-bold text-white dark:text-primary">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-sm text-white/80 dark:text-secondary">@yield('page-description', 'Manage your certificate applications')</p>
                        </div>
                    </div>
                    
                    <!-- Header Controls - Fixed Click Handling -->
                    <div class="flex items-center space-x-4">
                        <!-- FIXED Dark Mode Toggle -->
                        <button 
                            @click.stop="darkMode = !darkMode" 
                            class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white/20 dark:bg-gray-700 hover:bg-white/30 dark:hover:bg-gray-600 transition-colors"
                        >
                            <svg x-show="!darkMode" class="w-5 h-5 text-white dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </button>

                        <!-- FIXED User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button 
                                @click.stop="open = !open" 
                                class="flex items-center text-sm rounded-lg hover:bg-white/20 dark:hover:bg-gray-700 p-2 transition-colors"
                            >
                                <div class="w-8 h-8 user-initials rounded-full flex items-center justify-center mr-3">
                                    <span class="text-xs font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                </div>
                                <div class="text-left hidden sm:block">
                                    <div class="text-sm font-medium text-white dark:text-primary">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-white/80 dark:text-secondary">Administrator</div>
                                </div>
                                <svg class="ml-2 h-4 w-4 text-white/80 dark:text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu with Fixed Click Handling -->
                            <div 
                                x-show="open" 
                                @click.away="open = false" 
                                x-transition 
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-600 z-50"
                                style="z-index: 1000;"
                            >
                                <div class="py-1">
                                    <a href="#" @click.stop class="group flex items-center px-4 py-2 text-sm text-primary hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                                    <a href="#" @click.stop class="group flex items-center px-4 py-2 text-sm text-primary hover:bg-gray-100 dark:hover:bg-gray-700">Settings</a>
                                </div>
                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" @click.stop class="group flex items-center w-full px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main class="flex-1 relative z-1 py-6 px-4 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if(session('success') || session('error'))
                <div class="mb-6">
                    <div class="rounded-md {{ session('success') ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' }} p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                @if(session('success'))
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium {{ session('success') ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                                    {{ session('success') ?? session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="w-full">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>

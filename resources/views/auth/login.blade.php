<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In - Certificate Management System</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon-white.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('images/favicon-white.svg') }}" type="image/svg+xml">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js CDN -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        :root {
            --brand-blue: #006cb2;
            --brand-dark-blue: #003a68;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
        }
        
        /* Enhanced gradient background with certificate theme */
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 25%, #7c3aed  50%, #2563eb 75%, #1d4ed8 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        .dark .gradient-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 25%, #312e81 50%, #1e3a8a 75%, #0f172a 100%);
        }
        
        /* Certificate-themed decorative elements */
        .cert-decoration {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
        
        .cert-decoration::before {
            content: '';
            position: absolute;
            top: 10%;
            left: 5%;
            width: 200px;
            height: 150px;
            background: rgba(255, 255, 255, 0.03);
            border: 2px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            transform: rotate(-15deg);
        }
        
        .cert-decoration::after {
            content: '';
            position: absolute;
            bottom: 15%;
            right: 8%;
            width: 180px;
            height: 130px;
            background: rgba(255, 255, 255, 0.04);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            transform: rotate(12deg);
        }
        
        /* Floating certificate icons */
        .floating-icons {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
        
        .cert-icon {
            position: absolute;
            opacity: 0.06;
            animation: float 20s infinite linear;
        }
        
        .dark .cert-icon {
            opacity: 0.03;
        }
        
        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.06; }
            90% { opacity: 0.06; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }
        
        .cert-icon:nth-child(1) { left: 10%; animation-delay: -5s; }
        .cert-icon:nth-child(2) { left: 25%; animation-delay: -12s; }
        .cert-icon:nth-child(3) { left: 70%; animation-delay: -8s; }
        .cert-icon:nth-child(4) { left: 85%; animation-delay: -15s; }
        
        /* Main container */
        .login-card {
            background: transparent;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            position: relative;
            z-index: 10;
        }
        
        .dark .login-card {
            background: transparent;
        }
        
        /* Form panel */
        .w-full.lg\:w-1\/2 {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 0 20px 20px 0;
            width: 55%;
            padding: 3rem 2.5rem;
            display: flex;
            align-items: center;
        }
        
        .dark .w-full.lg\:w-1\/2 {
            background: rgba(17, 24, 39, 0.95);
        }
        
        /* Brand panel */
        .brand-panel {
            background: linear-gradient(135deg, var(--brand-blue) 0%, var(--brand-dark-blue) 100%);
            position: relative;
            overflow: hidden;
            border-radius: 20px 0 0 20px;
            box-shadow: 10px 0 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 1;
            width: 45%;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .brand-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 0%, transparent 100%);
        }
        
        /* Add subtle certificate pattern to brand panel */
        .brand-panel::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            opacity: 0.3;
        }
        
        /* Form inputs */
        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--brand-blue);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(0, 108, 178, 0.1);
        }
        
        .dark .form-input {
            background: #374151;
            border-color: #4b5563;
            color: #f3f4f6;
        }
        
        .dark .form-input:focus {
            background: #1f2937;
            border-color: var(--brand-blue);
            box-shadow: 0 0 0 3px rgba(0, 108, 178, 0.2);
        }
        
        /* Button */
        .btn-primary {
            background: linear-gradient(135deg, var(--brand-blue), var(--brand-dark-blue));
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 58, 104, 0.3);
        }
        
        /* Theme toggle */
        .theme-toggle {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 1000;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .theme-toggle:hover {
            transform: scale(1.1);
            background: rgba(255, 255, 255, 0.3);
        }
        
        /* PERFECT LOGO SOLUTION - WHITE LOGO IN DARK MODE */
        .logo-container {
            width: 260px !important;
            height: 130px !important;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .logo-svg {
            width: 260px !important;
            height: 130px !important;
            min-width: 260px !important;
            max-width: 260px !important;
            min-height: 130px !important;
            max-height: 130px !important;
            object-fit: contain;
            object-position: center;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            /* Make logo white in both light and dark modes */
            filter: brightness(0) invert(1);
        }
        
        /* Dark mode - make logo white/visible */
        .dark .logo-svg {
            filter: brightness(0) invert(1);
        }
        
        .logo-container-small {
            width: 200px !important;
            height: 100px !important;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .logo-svg-small {
            width: 200px !important;
            height: 100px !important;
            min-width: 200px !important;
            max-width: 200px !important;
            min-height: 100px !important;
            max-height: 100px !important;
            object-fit: contain;
            object-position: center;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            /* Light mode - normal logo */
            filter: none;
        }
        
        /* Dark mode - make small logo white/visible */
        .dark .logo-svg-small {
            filter: brightness(0) invert(1);
        }
        
        .app-name {
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            color: rgba(255, 255, 255, 0.95);
            margin-top: 12px;
            line-height: 1.3;
        }
        
        .app-name-form {
            font-size: 1.25rem;
            font-weight: 700;
            text-align: center;
            color: var(--brand-dark-blue);
            margin-top: 12px;
            margin-bottom: 24px;
            line-height: 1.3;
        }
        
        .dark .app-name-form {
            color: #f9fafb;
        }
        
        /* Always white footer */
        .footer-text {
            color: #ffffff !important;
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .brand-panel {
                display: none;
            }
            .login-card {
                border-radius: 20px;
            }
        }
        
        /* Text colors for dark mode */
        .text-primary {
            color: #111827;
        }
        
        .dark .text-primary {
            color: #f9fafb;
        }
        
        .text-secondary {
            color: #6b7280;
        }
        
        .dark .text-secondary {
            color: #d1d5db;
        }
    </style>
</head>
<body>
    <!-- Theme Toggle -->
    <button 
        @click="darkMode = !darkMode" 
        class="theme-toggle"
        aria-label="Toggle theme"
    >
        <svg x-show="!darkMode" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        
        <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>
    </button>

    <!-- Main Container -->
    <div class="gradient-bg flex items-center justify-center min-h-screen p-4">
        <!-- Certificate decorations -->
        <div class="cert-decoration"></div>
        
        <!-- Floating certificate icons -->
        <div class="floating-icons">
            <!-- Certificate icon 1 -->
            <svg class="cert-icon w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                <path d="M14 2v6h6"/>
                <path d="M16 13H8"/>
                <path d="M16 17H8"/>
                <path d="M10 9H8"/>
            </svg>
            
            <!-- Award icon -->
            <svg class="cert-icon w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                <path d="M5.166 12.95a3 3 0 01.475-1.268L7.5 9l.5-2.5L10.5 5l3 1.5L16 5l2.5 1.682L19 9l1.859 2.682a3 3 0 01.475 1.268L20.25 16.5l-1.916 3.432a3 3 0 01-1.268.475L14.5 21l-2.5-.593L9.5 21l-2.566-1.093a3 3 0 01-1.268-.475L3.75 16.5l1.084-3.55z"/>
            </svg>
            
            <!-- Shield icon -->
            <svg class="cert-icon w-11 h-11" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            
            <!-- Star icon -->
            <svg class="cert-icon w-9 h-9" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
        </div>
        
        <div class="w-full max-w-4xl">
            <div class="login-card overflow-hidden">
                <div class="flex min-h-[600px]">
                    <!-- Left Panel - Brand -->
                    <div class="brand-panel hidden lg:flex lg:w-1/2 p-12 flex-col justify-center text-white relative">
                        <div class="relative z-10">
                            <!-- Logo Section with App Name -->
                            <div class="text-center mb-8">
                                <!-- SINGLE LOGO WITH CSS FILTER FOR WHITE IN DARK MODE -->
                                <div class="logo-container">
                                    <img 
                                        src="{{ asset('images/logo_light.svg') }}" 
                                        alt="Users Software Systems Logo" 
                                        class="logo-svg"
                                    />
                                </div>
                                
                                <!-- App Name Below Logo -->
                                <div class="app-name">
                                    Certificate Management System
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="space-y-6">
                                <div>
                                    <p class="text-xl opacity-90 leading-relaxed">
                                        Streamline your certification workflow with our secure, modern platform designed for excellence.
                                    </p>
                                </div>
                                
                                <!-- Features -->
                                <div class="space-y-4 pt-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        </div>
                                        <span>Enterprise-grade security</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                                                <path d="M14 2v6h6"/>
                                            </svg>
                                        </div>
                                        <span>Digital certificate processing</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        <span>Real-time verification</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel - Login Form -->
                    <div class="w-full lg:w-1/2 p-8 lg:p-12 flex items-center justify-center relative">
                        <div class="w-full max-w-sm">
                            <!-- Mobile Logo and App Name -->
                            <div class="lg:hidden text-center mb-8">
                                <div class="logo-container-small">
                                    <img 
                                        src="{{ asset('images/logo_light.svg') }}" 
                                        alt="Users Software Systems Logo" 
                                        class="logo-svg-small"
                                    />
                                </div>
                                
                                <!-- App Name Below Logo -->
                                <div class="app-name-form">
                                    Certificate Management System
                                </div>
                            </div>
                            
                            <!-- Header -->
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-primary mb-2">Welcome Back</h2>
                                <p class="text-secondary">Sign in to your account</p>
                            </div>

                            <!-- Login Form -->
                            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                                @csrf

                                <!-- Session Status -->
                                @if (session('status'))
                                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm text-green-700 dark:text-green-300">{{ session('status') }}</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Email -->
                                <div class="space-y-2">
                                    <label for="email" class="block text-sm font-medium text-primary">
                                        Email Address
                                    </label>
                                    <input 
                                        id="email" 
                                        name="email" 
                                        type="email" 
                                        autocomplete="email" 
                                        required 
                                        value="{{ old('email') }}"
                                        class="form-input @error('email') border-red-300 dark:border-red-500 @enderror"
                                        placeholder="Enter your email"
                                    >
                                    @error('email')
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="space-y-2">
                                    <label for="password" class="block text-sm font-medium text-primary">
                                        Password
                                    </label>
                                    <input 
                                        id="password" 
                                        name="password" 
                                        type="password" 
                                        autocomplete="current-password" 
                                        required
                                        class="form-input @error('password') border-red-300 dark:border-red-500 @enderror"
                                        placeholder="Enter your password"
                                    >
                                    @error('password')
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Remember Me & Forgot Password -->
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center">
                                        <input 
                                            id="remember_me" 
                                            name="remember" 
                                            type="checkbox" 
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                                        >
                                        <span class="ml-2 text-sm text-secondary">Remember me</span>
                                    </label>

                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                            Forgot password?
                                        </a>
                                    @endif
                                </div>

                                <!-- Sign In Button -->
                                <button type="submit" class="btn-primary">
                                    Sign In to Dashboard
                                </button>

                                <!-- Copyright inside the form -->
                                <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-center text-xs text-gray-500 dark:text-gray-400">
                                        &copy; {{ date('Y') }} Certificate Management System. All rights reserved.
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

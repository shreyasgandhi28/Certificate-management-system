<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Certificate Management System</title>
    
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
        
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 25%, #7c3aed 50%, #2563eb 75%, #1d4ed8 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
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
        
        .dark .gradient-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 25%, #312e81 50%, #1e3a8a 75%, #0f172a 100%);
        }
        
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
        
        /* Form elements */
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            transition: all 0.2s;
            font-size: 0.9375rem;
            color: #1f2937;
        }
        
        .dark .form-input {
            background: #1f2937;
            border-color: #4b5563;
            color: #f9fafb;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--brand-blue);
            box-shadow: 0 0 0 3px rgba(0, 108, 178, 0.1);
        }
        
        .dark .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .btn-primary {
            background: var(--brand-blue);
            color: white;
            font-weight: 500;
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
            width: 100%;
            text-align: center;
            cursor: pointer;
            border: none;
            margin-top: 0.5rem;
        }
        
        .btn-primary:hover {
            background: #005a9c;
            transform: translateY(-1px);
        }
        
        .dark .btn-primary {
            background: #3b82f6;
        }
        
        .dark .btn-primary:hover {
            background: #2563eb;
        }
        
        /* Theme toggle */
        .theme-toggle {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 50;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .dark .theme-toggle {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .theme-toggle:hover {
            transform: scale(1.1);
            background: rgba(0, 0, 0, 0.3);
        }
        
        .footer-text {
            color: #ffffff;
            opacity: 0.9;
        }
        
        /* Logo container for both light and dark modes */
        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        .logo-svg {
            height: 100px;
            width: auto;
            filter: brightness(0) invert(1);
            transition: all 0.3s ease;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .app-name {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: 0.025em;
            margin: 1rem 0 3rem;
            line-height: 1.2;
            text-align: center;
        }
        
        /* Dark mode text improvements */
        .dark .text-primary {
            color: #f9fafb;
        }
        
        .dark .text-secondary {
            color: #d1d5db;
        }
        
        .dark label {
            color: #e5e7eb;
        }
        
        .dark .form-input::placeholder {
            color: #9ca3af;
        }
        
        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .brand-panel {
                display: none;
            }
            
            .w-full.lg\:w-1\/2 {
                width: 100%;
                border-radius: 20px;
                padding: 2.5rem 2rem;
            }
            
            .flex.min-h-\[600px\] {
                min-height: auto;
                max-width: 500px;
            }
            
            .app-name {
                font-size: 1.5rem;
                margin: 1rem 0 2rem;
            }
            
            .logo-svg {
                height: 80px;
            }
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
        <!-- Sun icon for light mode -->
        <svg x-show="!darkMode" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        
        <!-- Moon icon for dark mode -->
        <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>
    </button>

    <div class="gradient-bg flex items-center justify-center min-h-screen p-4">
        <!-- Certificate decorations -->
        <div class="cert-decoration"></div>
        
        <!-- Floating certificate icons -->
        <div class="floating-icons">
            <svg class="cert-icon" width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 3C2.9 3 2 3.9 2 5V19C2 20.1 2.9 21 4 21H20C21.1 21 22 20.1 22 19V5C22 3.9 21.1 3 20 3H4ZM19 18H5C4.45 18 4 17.55 4 17V6C4 5.45 4.45 5 5 5H19C19.55 5 20 5.45 20 6V17C20 17.55 19.55 18 19 18Z" fill="currentColor"/>
                <path d="M14 17H7V15H14V17ZM17 13H7V11H17V13ZM17 9H7V7H17V9Z" fill="currentColor"/>
            </svg>
            <svg class="cert-icon" width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM19 19H5V5H19V19Z" fill="currentColor"/>
                <path d="M14.17 8.58L15.58 10L14.17 11.41L12.76 10L14.17 8.58ZM9.41 10L11.05 11.64L12.47 10.22L10.83 8.58L9.41 10ZM16.34 14.23L14.93 15.64L10.29 11L11.7 9.59L16.34 14.23Z" fill="currentColor"/>
            </svg>
            <svg class="cert-icon" width="35" height="35" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM19 19H5V5H19V19Z" fill="currentColor"/>
                <path d="M14.5 13.5H16V10.5H14.5V13.5ZM7.5 10.5V12H12V10.5H7.5ZM16.5 15.5H7.5V17H16.5V15.5Z" fill="currentColor"/>
            </svg>
            <svg class="cert-icon" width="45" height="45" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM19 19H5V5H19V19Z" fill="currentColor"/>
                <path d="M14.5 10.5H16V13.5H14.5V10.5ZM7.5 10.5H13V12H7.5V10.5ZM7.5 13.5H16V15H7.5V13.5Z" fill="currentColor"/>
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
                                        alt="Certificate Management System Logo" 
                                        class="logo-svg"
                                    />
                                </div>
                                <div class="app-name">
                                    Certificate Management System
                                </div>
                            </div>
                            
                            <h1 class="text-4xl font-bold mb-8 leading-tight">Reset Your Password</h1>
                            <p class="text-blue-100 text-xl leading-relaxed opacity-90">
                                Create a new secure password to access your account.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Right Panel - Form -->
                    <div class="w-full lg:w-1/2 p-8 lg:px-12 lg:py-8 flex items-center justify-center">
                        <div class="w-full max-w-sm">
                            <!-- Mobile Logo and App Name -->
                            <div class="lg:hidden text-center mb-10">
                                <!-- SINGLE LOGO WITH CSS FILTER FOR WHITE IN DARK MODE -->
                                <div class="logo-container">
                                    <img 
                                        src="{{ asset('images/logo_light.svg') }}" 
                                        alt="Certificate Management System Logo" 
                                        class="logo-svg"
                                    />
                                </div>
                                <div class="app-name">
                                    Certificate Management System
                                </div>
                                <h2 class="text-3xl font-bold text-primary mt-8 mb-3">Reset Password</h2>
                                <p class="text-secondary text-base leading-relaxed mb-8">Create a new secure password for your account</p>
                            </div>

                            <!-- Session Status -->
                            <x-auth-session-status class="mb-6" :status="session('status')" />

                            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                                @csrf

                                <!-- Password Reset Token -->
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <div class="space-y-5">
                                    <!-- Email -->
                                    <div class="form-group">
                                        <label for="email" class="block text-sm font-medium text-primary mb-2">
                                            Email Address
                                        </label>
                                        <input 
                                            id="email" 
                                            name="email" 
                                            type="email" 
                                            value="{{ old('email', $request->email) }}" 
                                            required 
                                            readonly
                                            class="form-input w-full bg-gray-50 dark:bg-gray-700 cursor-not-allowed @error('email') border-red-300 dark:border-red-500 @enderror"
                                        >
                                        @error('email')
                                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group">
                                        <label for="password" class="block text-sm font-medium text-primary mb-2">
                                            New Password
                                        </label>
                                        <input 
                                            id="password" 
                                            name="password" 
                                            type="password" 
                                            required 
                                            class="form-input w-full @error('password') border-red-300 dark:border-red-500 @enderror"
                                            placeholder="Enter new password"
                                        >
                                        @error('password')
                                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="form-group">
                                        <label for="password_confirmation" class="block text-sm font-medium text-primary mb-2">
                                            Confirm New Password
                                        </label>
                                        <input 
                                            id="password_confirmation" 
                                            name="password_confirmation" 
                                            type="password" 
                                            required 
                                            class="form-input w-full"
                                            placeholder="Confirm new password"
                                        >
                                    </div>

                                    <div class="pt-1">
                                        <button type="submit" class="btn-primary w-full">
                                            Reset Password
                                        </button>
                                    </div>

                                    <div class="text-center pt-1">
                                        <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                            ← Back to Sign In
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 text-center">
        <p class="text-xs footer-text">
            &copy; {{ date('Y') }} Certificate Management System. All rights reserved.
        </p>
    </div>
</body>
</html>

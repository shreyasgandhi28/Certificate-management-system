@extends('layouts.admin')
@section('title', 'Create User')
@section('page-title', 'Create User')
@section('page-description', 'Add a new user and assign roles')

@push('styles')
<style>
    .select2-container--default .select2-selection--multiple {
        background-color: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        min-height: 42px;
        padding: 0.25rem 0.5rem;
    }
    .dark .select2-container--default .select2-selection--multiple {
        background-color: #1f2937;
        border-color: #374151;
        color: #f3f4f6;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #e0f2fe;
        border: 1px solid #bae6fd;
        border-radius: 0.25rem;
        color: #0369a1;
        padding: 0.25rem 0.5rem;
    }
    .dark .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #1e3a8a;
        border-color: #1e40af;
        color: #bfdbfe;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #0369a1;
        margin-right: 0.25rem;
    }
    .dark .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #bfdbfe;
    }
    .select2-dropdown {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
    }
    .dark .select2-dropdown {
        background-color: #1f2937;
        border-color: #374151;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #dbeafe;
        color: #1e40af;
    }
    .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #1e3a8a;
        color: #bfdbfe;
    }
</style>
@endpush

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to List
        </a>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
        @csrf
        
        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/10 border-l-4 border-red-500 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            There {{ $errors->count() === 1 ? 'is' : 'are' }} {{ $errors->count() }} {{ Str::plural('error', $errors->count()) }} with your submission
                        </h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" required>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Password
                        <span class="text-xs font-normal text-gray-500 dark:text-gray-400 ml-1">(Must be at least 8 characters with uppercase, lowercase, number & special character)</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" 
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$"
                            title="Must contain at least 8 characters, including uppercase, lowercase, number and special character"
                            required
                        >
                        <div id="password-strength" class="mt-1 text-xs"></div>
                    </div>
                    <div id="password-requirements" class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <p class="font-medium mb-1">Password must contain:</p>
                        <ul class="space-y-1">
                            <li id="req-length" class="flex items-center">
                                <span id="length-check" class="inline-block w-4 h-4 mr-1 text-red-500">✗</span>
                                <span>At least 8 characters</span>
                            </li>
                            <li id="req-uppercase" class="flex items-center">
                                <span id="uppercase-check" class="inline-block w-4 h-4 mr-1 text-red-500">✗</span>
                                <span>At least one uppercase letter</span>
                            </li>
                            <li id="req-lowercase" class="flex items-center">
                                <span id="lowercase-check" class="inline-block w-4 h-4 mr-1 text-red-500">✗</span>
                                <span>At least one lowercase letter</span>
                            </li>
                            <li id="req-number" class="flex items-center">
                                <span id="number-check" class="inline-block w-4 h-4 mr-1 text-red-500">✗</span>
                                <span>At least one number</span>
                            </li>
                            <li id="req-special" class="flex items-center">
                                <span id="special-check" class="inline-block w-4 h-4 mr-1 text-red-500">✗</span>
                                <span>At least one special character</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="confirm-password"
                        class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" 
                        required
                    >
                    <div id="password-match" class="mt-1 text-xs text-red-500"></div>
                </div>
            </div>

            <div x-data="{ open: false, selectedRoles: {{ json_encode(old('roles', [])) }} }">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Roles</label>
                
                <!-- Hidden input field for form submission -->
                <template x-for="(role, index) in selectedRoles" :key="index">
                    <input type="hidden" name="roles[]" :value="role">
                </template>
                
                <!-- Dropdown toggle button -->
                <div class="relative z-50">
                    <button 
                        @click="open = !open" 
                        type="button"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900"
                        :class="{ 'ring-2 ring-blue-500': open }"
                    >
                        <span class="truncate" x-text="selectedRoles.length ? selectedRoles.join(', ') : 'Select roles...'"></span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- Dropdown menu -->
                    <div 
                        x-show="open"
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute mt-1 w-full bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50"
                        style="display: none;"
                    >
                        <div class="space-y-2 p-2">
                            @foreach($roles as $role)
                                <div class="flex items-center px-3 py-1.5">
                                    <input 
                                        type="checkbox"
                                        :id="'role-{{ $role }}'"
                                        :value="'{{ $role }}'"
                                        x-model="selectedRoles"
                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:border-gray-600"
                                    >
                                    <label :for="'role-{{ $role }}'" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $role }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                @error('roles')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                Create User
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password validation
        function validatePassword(input) {
            const password = input.value;
            const strengthText = document.getElementById('password-strength');
            
            // Reset all checks
            const lengthCheck = document.getElementById('length-check');
            const uppercaseCheck = document.getElementById('uppercase-check');
            const lowercaseCheck = document.getElementById('lowercase-check');
            const numberCheck = document.getElementById('number-check');
            const specialCheck = document.getElementById('special-check');
            
            // Reset classes and icons
            [lengthCheck, uppercaseCheck, lowercaseCheck, numberCheck, specialCheck].forEach(el => {
                el.textContent = '✗';
                el.classList.remove('text-green-500');
                el.classList.add('text-red-500');
            });

            // Check length
            if (password.length >= 8) {
                lengthCheck.textContent = '✓';
                lengthCheck.classList.remove('text-red-500');
                lengthCheck.classList.add('text-green-500');
            }

            // Check uppercase
            if (/[A-Z]/.test(password)) {
                uppercaseCheck.textContent = '✓';
                uppercaseCheck.classList.remove('text-red-500');
                uppercaseCheck.classList.add('text-green-500');
            }

            // Check lowercase
            if (/[a-z]/.test(password)) {
                lowercaseCheck.textContent = '✓';
                lowercaseCheck.classList.remove('text-red-500');
                lowercaseCheck.classList.add('text-green-500');
            }

            // Check number
            if (/\d/.test(password)) {
                numberCheck.textContent = '✓';
                numberCheck.classList.remove('text-red-500');
                numberCheck.classList.add('text-green-500');
            }

            // Check special character
            if (/[^A-Za-z0-9]/.test(password)) {
                specialCheck.textContent = '✓';
                specialCheck.classList.remove('text-red-500');
                specialCheck.classList.add('text-green-500');
            }

            // Update password strength
            if (password.length === 0) {
                strengthText.textContent = '';
                strengthText.className = 'mt-1 text-xs';
            } else if (password.length < 4) {
                strengthText.textContent = 'Very Weak';
                strengthText.className = 'mt-1 text-xs text-red-500';
            } else if (password.length < 8) {
                strengthText.textContent = 'Weak';
                strengthText.className = 'mt-1 text-xs text-orange-500';
            } else if (/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/.test(password)) {
                strengthText.textContent = 'Strong';
                strengthText.className = 'mt-1 text-xs text-green-500';
            } else {
                strengthText.textContent = 'Medium';
                strengthText.className = 'mt-1 text-xs text-yellow-500';
            }

            // Also validate password match
            validatePasswordMatch();
        }

        function validatePasswordMatch() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm-password');
            const matchText = document.getElementById('password-match');
            
            if (!password || !confirmPassword || !matchText) return;
            
            if (confirmPassword.value.length === 0) {
                matchText.textContent = '';
            } else if (password.value === confirmPassword.value) {
                matchText.textContent = 'Passwords match';
                matchText.className = 'mt-1 text-xs text-green-500';
            } else {
                matchText.textContent = 'Passwords do not match';
                matchText.className = 'mt-1 text-xs text-red-500';
            }
        }

        // Initialize event listeners
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm-password');
        
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                validatePassword(this);
            });
        }
        
        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', validatePasswordMatch);
        }
    });
</script>
@endpush

@endsection


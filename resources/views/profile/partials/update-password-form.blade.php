<form method="post" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <div class="space-y-4">
        <!-- Current Password -->
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('Current Password') }}
            </label>
            <input 
                type="password" 
                id="update_password_current_password" 
                name="current_password" 
                required 
                autocomplete="current-password"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
            @error('current_password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Password -->
        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('New Password') }}
            </label>
            <input 
                type="password" 
                id="update_password_password" 
                name="password" 
                required 
                autocomplete="new-password"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
            @error('password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('Confirm Password') }}
            </label>
            <input 
                type="password" 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
            @error('password_confirmation', 'updatePassword')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center justify-end pt-4">
        @if (session('status') === 'password-updated')
            <div class="mr-4 text-sm text-green-600 dark:text-green-400">
                {{ __('Saved.') }}
            </div>
        @endif
        
        <button 
            type="submit"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
            {{ __('Save') }}
        </button>
    </div>
</form>

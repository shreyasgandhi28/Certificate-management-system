<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <div class="space-y-4">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('Name') }}
            </label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name', $name) }}" 
                required 
                autofocus 
                autocomplete="name"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
            @error('name')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                {{ __('Email') }}
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email', $email) }}" 
                required 
                autocomplete="username"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-md">
                    <p class="text-sm text-yellow-700 dark:text-yellow-400">
                        {{ __('Your email address is unverified.') }}
                        <button 
                            type="button" 
                            form="send-verification" 
                            class="ml-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium"
                        >
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="flex items-center justify-end pt-4">
        <button 
            type="submit"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
            {{ __('Save') }}
        </button>
    </div>
</form>
